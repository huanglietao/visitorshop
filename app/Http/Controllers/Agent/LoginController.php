<?php
namespace App\Http\Controllers\Agent;


use App\Models\DmsMerchantAccount;
use App\Repositories\DmsMerchantAccountRepository;
use App\Repositories\OmsAgentDeployRepository;
use App\Repositories\SaasArticleRepository;
use App\Services\Geetest\GeetestLib;
use App\Services\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\OmsMerchantAccount;


/**
 * 控制台
 *
 * 展示统计及报表的相关数据
 * @author: yanxs <541139655@qq.com>
 * @version: 1.0
 * @date: 2019/10/12
 */

class LoginController extends BaseController
{

    protected $viewPath = 'agent.login';    //当前控制器所的view所在的目录
    protected $modules = 'auth';            //当前控制器所属模块
    protected $sysId = 'agent';             //当前控制器所属模块
    protected $noNeedRight = ['*'];
    protected $deployInfo;                  //站点配置信息


    public function __construct(DmsMerchantAccount $merchantModel,DmsMerchantAccountRepository $Repository,
                                OmsAgentDeployRepository $agentDeployRepository,SaasArticleRepository $articleRepository)
    {
        parent::__construct();
        $this->model = $merchantModel;
        $this->repositories = $Repository;

        $this->deployInfo = $agentDeployRepository->getDeployInfo($this->agent_mid);
        //获取最新发布的文章
        $this->wtArtList = $articleRepository->getRows(['art_sign'=>GOODS_MAIN_CATEGORY_HELP,'is_open'=>ONE],'created_at','desc',4);

    }

    public function index()
    {
        //判断端口为手机端还是pc端 by .david
        $is_mobile = $this->isMobile();
        if($is_mobile){
            return view('agent.mlogin',['deployInfo'=>$this->deployInfo,'systemInfo'=>$this->systemInfo,'wtArtList'=>$this->wtArtList]);
        }else{
            return view('agent.login',['deployInfo'=>$this->deployInfo,'systemInfo'=>$this->systemInfo,'wtArtList'=>$this->wtArtList]);
        }

    }

    //登录处理
    public function savelogin(Request $request)
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


        $user_info = json_decode($this->model->where(['dms_adm_username' => $post['username'],'dms_adm_status'=>1])->get(),true);

        if (empty($user_info)){
            $res_arr = [
                'code'  => 004,
                'message'=>'账号或密码错误',
            ];
            return $res_arr;
        }else{
            $sys_password = $this->repositories->getPassword($post['password'],$user_info[0]['dms_adm_salt']);

            //密码验证
            if ($user_info[0]['dms_adm_password']!=$sys_password){
                //密码错误
                $res_arr = [
                    'code'  => 004,
                    'message'=>'账号或密码错误',
                ];
                return $res_arr;
            }
            $user_info[0]['admin_id'] = $user_info[0]['dms_adm_id'];
            //写进session
            session(['admin' => $user_info[0]]);
            //更新登录时间
            $this->model->where(['dms_adm_username' => $post['username'],'dms_adm_password'=>$sys_password])->update(['dms_adm_logintime' => time()]);
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
        //echo $GtSdk->get_response_str();
        return \response()->json($GtSdk->get_response());


    }

    //退出登录
    public function logOut(Request $request)
    {
        $request->session()->forget('admin');
        return redirect('/');
    }

}