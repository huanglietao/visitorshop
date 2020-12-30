<?php
namespace App\Http\Controllers\Erp;

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

}