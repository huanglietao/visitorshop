<?php
namespace App\Http\Controllers\Erpkf;

use App\Http\Controllers\Erpkf\BaseController;
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
    protected $viewPath = 'erpkf.login';  //当前控制器所的view所在的目录
    protected $modules = 'sys';        //当前控制器所属模块
    protected $noLogin = ['*'];         //免登录验证

    public function __construct(VipCustomerRepository $Repository)
    {
        parent::__construct();
        $this->repositories = $Repository;
    }

    public function index()
    {

        return view('erpkf.login.index');
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
        $ret_GtSdk = $this->getGeetes($post['username'],session('status'));

        if($ret_GtSdk == 'fail'){ //极验证码服务器出错或非法请求
            $res_arr = [
                'code'  => 004,
                'message'=>'验证未通过，请重新验证',
            ];
            return $res_arr;
        }

        $user_info = json_decode(DB::table("erp_kf_users")->where(['username' => $post['username'],'password'=>md5(md5($post['password']) . 'erpkf')])->get(),true);

        if (empty($user_info)){
            $res_arr = [
                'code'  => 004,
                'message'=>'账号或密码错误',
            ];
            return $res_arr;
        }else{
            //写进session
            session(['capital' => $user_info[0]]);
            $res_arr = [
                'code'  => 1,
                'message'=>'登录成功',
            ];
            return $res_arr;
        }

    }

    //极验证码处理
    public function getGeetes($user,$statu)
    {
        $ip = Helper::getClientIp();
        $GtSdk = new GeetestLib(config('common.geettest.captcha_id'),config('common.geettest.private_key'));

        $data = array(
            "user_id" => $user, # "test"  网站用户id
            "client_type" => "web", #web:电脑上的浏览器；h5:手机上的浏览器，包括移动应用内完全内置的web_view；native：通过原生SDK植入APP应用的方式
            "ip_address" => $ip, # 请在此处传输用户请求验证时所携带的IP
        );

        $status = $GtSdk->pre_process($data, 1);
        if($status==$statu){ //服务器正常
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

    }

    //验证码
    public function start()
    {
        $GtSdk = new GeetestLib(config('common.geettest.captcha_id'),config('common.geettest.private_key'));

       // session_start();
        $ip = Helper::getClientIp();
        $data = array(
            "user_id" => "test", # 网站用户id
            "client_type" => "web", #web:电脑上的浏览器；h5:手机上的浏览器，包括移动应用内完全内置的web_view；native：通过原生SDK植入APP应用的方式
            "ip_address" => $ip # 请在此处传输用户请求验证时所携带的IP
        );

        $status = $GtSdk->pre_process($data, 1);
        session(['status' => $status]);
        echo $GtSdk->get_response_str();


    }




}