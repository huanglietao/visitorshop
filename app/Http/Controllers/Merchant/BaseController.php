<?php
namespace App\Http\Controllers\Merchant;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
/**
 * 商户平台基础控制器
 *
 * 实现商户基础功能
 * @author: yanxs <541139655@qq.com>
 * @version: 1.0
 * @date: 2019/10/16
 */
class BaseController extends \App\Http\Controllers\BaseController
{
    protected $modules = 'sys';                                     //当前功能所属模块
    protected $sysId   = 'Merchant';                                //当前子系统
    protected $auth_group = 'oms_auth_group';                       //用户组数据表名
    protected $auth_rule = 'oms_auth_rule';                         //权限规则表
    protected $auth_user = 'oms_merchant_account';                  //用户信息表
    protected $system = 'oms';                                      //系统标识

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
        $breadcrumb = $this->getBreadCrumb($rule->oms_auth_rule_id);

        return $this->jsonSuccess(['remark'=>$rule->oms_auth_rule_remark,'breadcrumb'=>$breadcrumb]) ;
    }

}
