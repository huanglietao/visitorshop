<?php
namespace App\Http\Controllers\Erp;


use App\Services\Geetest\GeetestLib;




/**
 * 项目说明
 * 详细说明
 * @author:
 * @version: 1.0
 * @date:
 */
class GeetTestController extends BaseController
{
    protected $viewPath = 'erp.geettest';  //当前控制器所的view所在的目录
    protected $modules = 'sys';        //当前控制器所属模块
    protected $noLogin = ['*'];         //免登录验证



    //验证码
    public function start()
    {
        $GtSdk = new GeetestLib(config('common.geettest.captcha_id'),config('common.geettest.private_key'));

        session_start();

        $data = array(
            "user_id" => "test", # 网站用户id
            "client_type" => "web", #web:电脑上的浏览器；h5:手机上的浏览器，包括移动应用内完全内置的web_view；native：通过原生SDK植入APP应用的方式
            "ip_address" => "127.0.0.1" # 请在此处传输用户请求验证时所携带的IP
        );

        $status = $GtSdk->pre_process($data, 1);
        $_SESSION['gtserver'] = $status;
        $_SESSION['user_id'] = $data['user_id'];
        echo $GtSdk->get_response_str();


    }

}