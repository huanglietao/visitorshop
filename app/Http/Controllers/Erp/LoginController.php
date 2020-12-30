<?php
namespace App\Http\Controllers\Erp;

use App\Http\Controllers\Erp\BaseController;
use App\Http\Requests\Erp\LoginRequest;
use App\Repositories\VipCustomerRepository;
use App\Services\Geetest\GeetestLib;
use App\Services\Outer\Erp\Api;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Jobs\Notice;
use App\Services\Helper;

/**
 * 项目说明
 * 详细说明
 * @author:
 * @version: 1.0
 * @date:
 */
class LoginController extends BaseController
{
    protected $viewPath = 'erp.login';  //当前控制器所的view所在的目录
    protected $modules = 'sys';        //当前控制器所属模块
    protected $noLogin = ['*'];         //免登录验证

    public function __construct(VipCustomerRepository $Repository)
    {
        parent::__construct();
        $this->repositories = $Repository;
    }

    public function index()
    {

        return view('erp.login.index');
    }
    public function validatelogin(Request $request)
    {

        $see_time = time();
        if (session("capital")) {
            $userinfo = session("capital");
        }else{
            //未登录，返回登陆页面
             return redirect('login/index');
            //测试，直接写session
            /*$data = [
                'message' => "成功",
                'code' => 1,
                'data' => [
                    'partner_usable_money' => '0.03',
                    'partner_code'  => '1235479333',
                    'partner_name'  => "FTP测试客户",
                    'partner_real_name' => 'FTP测试客户',
                    'partner_mobile' => '13265961649',
                    'partner_k_money_usable' => '0.03',
                    'partner_login_name' => '1235479333'
                ],
            ];
            session(['capital' => $data]);*/
        }
        

        $sms_code = session("sms_code");
        $send_time = session('sms_send_time');





        $now_time = time();
        //获取上次获取验证码到现在经历了多少秒
        $count_time = $now_time - $send_time;

        if (60-$count_time<=0)
        {

            //过了60s,可以重新发送短信
            $is_send = 1;
        }else{
            //等经过60-$count_time之后才能发送短信
            $is_send = 0;
            $count_time = 60-$count_time;
        }

        if ($request->isMethod('post')){
            //验证验证码
            $post_data = $request->post();
            $add_time = date($send_time);
            $now_time = date(time());
            $add_cha = intval(($now_time - $add_time)/60);

            if ($add_cha<=5){
                $cod = $post_data['code'];

                if ($cod!=$sms_code){
                    //验证码错误
                    return response()->json(['status' => 0,'msg'=>"验证码错误"]);
                }
            }else{
                //验证码错误
                return response()->json(['status' => 0,'msg'=>"验证码已过期"]);
            }
            //添加登录标识
            session(['is_login' => "success"]);

            return response()->json(['status' => 1,'msg'=>"ok"]);

        }

        //将手机号中间4位换为*号
        $userinfo['data']['partner_mobile'] = substr_replace($userinfo['data']['partner_mobile'], '****',3, 4);


        return view('erp.login.checklogin',[
            'userinfo' => $userinfo['data'],
            'is_send' => $is_send,
            'count' => $count_time,
        ]);

    }

    //登录处理
    public function savelogin(LoginRequest $request)
    {
        $post = $request->all();
        $ret_GtSdk = $this->getGeetes($post['username']);

        if($ret_GtSdk == 'fail'){ //极验证码服务器出错或非法请求
            $res_arr = [
                'code'  => 004,
                'message'=>'验证未通过，请重新验证',
            ];
            return $res_arr;
        }

        $reqApi = new Api();
        $post_data = [
            'login_name'  => preg_replace('# #','',$post['username']),
            'login_password'  => md5(preg_replace('# #','',$post['password']).'&key=clouderpaccountdologin'),
            'login_mobile' => preg_replace('# #','',$post['phone']),
        ];
        $res_arr = $reqApi->request(config('erp.interface_url').config('erp.login'),$post_data);

        $ip = Helper::getClientIp();//获取ip

        if($res_arr['code'] == 1){
            $now_time = time();
            $data = $res_arr+['now_time'=>$now_time];

            session(['capital' => $data]); //保存到session

            //记录数据库判断是否第一次登录
            $userInfo = json_decode(DB::table("erp_vip_customer")->where('partner_login_name', $res_arr['data']['partner_login_name'])->get(),true);

            if($userInfo){
                DB::table('erp_vip_customer')
                    ->where('id', $userInfo[0]['id'])
                    ->update(['prevtime' =>$userInfo[0]['logintime'] ,'logintime' =>time(),'joinip'=>$ip]);
            }else{
                $ins_data = [
                    'partner_login_name'   => $res_arr['data']['partner_login_name'],
                    'nickname'             => $res_arr['data']['partner_real_name'],
                    'prevtime'             => time(),
                    'logintime'            => time(),
                    'joinip'               => $ip,
                    'created_at'           => time(),
                    'updated_at'           => time(),
                    'status'               => 1,
                    'partner_code'         => $res_arr['data']['partner_code']
                ];
                DB::table("erp_vip_customer")->insert($ins_data);
            }
        }
        return $res_arr;

    }

    //极验证码处理
    public function getGeetes($user)
    {
        $ip = Helper::getClientIp();
        $GtSdk = new GeetestLib(config('common.geettest.captcha_id'),config('common.geettest.private_key'));

        $data = array(
            "user_id" => $user, # "test"  网站用户id
            "client_type" => "web", #web:电脑上的浏览器；h5:手机上的浏览器，包括移动应用内完全内置的web_view；native：通过原生SDK植入APP应用的方式
            "ip_address" => $ip, # 请在此处传输用户请求验证时所携带的IP
        );

        $status = $GtSdk->pre_process($data, 1);
        if($status==1){ //服务器正常
            $result = $GtSdk->success_validate($_POST['geetest_challenge'], $_POST['geetest_validate'], $_POST['geetest_seccode'], $data);
            if ($result) {
                return 'success';
                echo '{"status":"success"}';
            } else{
                return 'fail';
                echo '{"status":"fail"}';
            }

        }else{  //服务器宕机,走failback模式
            if ($GtSdk->fail_validate($_POST['geetest_challenge'],$_POST['geetest_validate'],$_POST['geetest_seccode'])) {
                return 'success';
                echo '{"status":"success"}';
            }else{
                return 'fail';
                echo '{"status":"fail"}';
            }
        }
        echo $GtSdk->get_response_str();

    }
    public function sendsms(Request $request)
    {

        $post = $request->post();
        //生成验证码 用于发送验证码
        $code = mt_rand(100000,999999);
        $send_time = time();

        $user_info = session("capital");
        //var_dump($user_info);exit;
        //所需传入的数据
        $messageData = [
            'type' => 'sms',
            'options' => [
                'query' => [
                    'RegionId' => "cn-hangzhou",
                    'PhoneNumbers' => $user_info['data']['partner_mobile'],
                    'SignName' => "长荣云印刷",
                    'TemplateCode' => "SMS_181250296",
                    'TemplateParam' => "{'code':".$code."}",
                ]
            ]
        ];

        //放入消息通知队列
        $ret = Notice::dispatch($messageData)->onQueue('q1');


        //记录数据库
        $ins_data = [
            'mobile' => $post['mob'],
            'add_time' => $send_time,
            'code' => $code,
        ];
        DB::table("erp_isms_sms_log")->insert($ins_data);

        //更改session数据
        $send_time = time();


        session(['sms_code' => $code]);
        session(['sms_send_time' => time()]);


        return response()->json(['status' => 200]);

    }





}