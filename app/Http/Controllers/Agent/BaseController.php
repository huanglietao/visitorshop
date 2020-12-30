<?php
namespace App\Http\Controllers\Agent;

use App\Services\Helper;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

/**
 * 分销商基础控制器
 *
 * 实现分销商块的基础功能
 * @author: yanxs <541139655@qq.com>
 * @version: 1.0
 * @date: 2019/10/16
 */
class BaseController extends \App\Http\Controllers\BaseController
{
    protected $modules = 'sys';  //当前功能所属模块
    protected $sysId = 'agent'; //当前子系统
    protected $auth_group = 'dms_auth_group';                       //用户组数据表名
    protected $auth_rule = 'dms_auth_rule';                         //权限规则表
    protected $auth_user = 'dms_merchant_account';                  //用户信息表
    protected $system = 'dms';
    protected $agent_mid = '';
    protected $noCookie = '';   //不需要验证商家id
    /**
     *
     * BaseController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        //把当前模块注入容器
        app()->instance('sys_id',$this->sysId);
        app()->instance('modules',$this->modules);


        //所有方法不需要验证
        if($this->noCookie=="*"){
            return;
        }
        //获取不进行验证Cookie功能的方法串
        $noCookieArr = explode(',',$this->noCookie);
        //获取当前路由
        $method = $this->getControllerAndFunction();
        if(in_array($method['method'],$noCookieArr)){
            return;
        }

        //通过url取商户ID
        $mch_id = Helper::easyDecrypt(\request()->mid);

        if(empty($mch_id) && empty(Cookie::get('agent_mid'))){
            echo "<span style='text-align: center'><h1>404  页面已失效</h1><hr><h4>请联系商家重新获取链接</h4></span>";
            die;
        }else{
            if(!empty($mch_id)) {
                //商家ID不为空，则将商家ID重新存入Cookie
                Cookie::queue('agent_mid',$mch_id, 365 * 24 * 60);
                $this->agent_mid = $mch_id;
            }else{
                $this->agent_mid = Cookie::get('agent_mid');
            }
        }

    }

    //判断是否为手机登录
    function isMobile(){
        $useragent=isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
        $useragent_commentsblock=preg_match('|\(.*?\)|',$useragent,$matches)>0?$matches[0]:'';
        function CheckSubstrs($substrs,$text){
            foreach($substrs as $substr)
                if(false!==strpos($text,$substr)){
                    return true;
                }
            return false;
        }
        $mobile_os_list=array('Google Wireless Transcoder','Windows CE','WindowsCE','Symbian','Android','armv6l','armv5','Mobile','CentOS','mowser','AvantGo','Opera Mobi','J2ME/MIDP','Smartphone','Go.Web','Palm','iPAQ');
        $mobile_token_list=array('Profile/MIDP','Configuration/CLDC-','160×160','176×220','240×240','240×320','320×240','UP.Browser','UP.Link','SymbianOS','PalmOS','PocketPC','SonyEricsson','Nokia','BlackBerry','Vodafone','BenQ','Novarra-Vision','Iris','NetFront','HTC_','Xda_','SAMSUNG-SGH','Wapaka','DoCoMo','iPhone','iPod');

        $found_mobile=CheckSubstrs($mobile_os_list,$useragent_commentsblock) ||
            CheckSubstrs($mobile_token_list,$useragent);

        if ($found_mobile){
            return true;
        }else{
            return false;
        }
    }

    //面包屑和提示操作说明功能
    function getRuleRemarkAndBcrumb(Request $request)
    {
        $post = $inputs = $request->all();
        $path = parse_url($post['url']);

        $rule = DB::table($this->system.'_auth_rule')
            ->where($this->system.'_auth_rule_name',substr($path['path'],1))
            ->first();
        if(empty($rule)){//避免未定义的菜单报错
            return $this->jsonFailed('未定义菜单规则');
        }
        $breadcrumb = $this->getBreadCrumb($rule->dms_auth_rule_id);

        return $this->jsonSuccess(['remark'=>$rule->dms_auth_rule_remark,'breadcrumb'=>$breadcrumb]) ;
    }




}