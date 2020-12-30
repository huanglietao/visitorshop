<?php
namespace App\Http\Controllers\Erpkf;

use Illuminate\Http\Request;
/**
 * Erpkf基础控制器
 *
 * 实现erp客服的基础功能
 * @author: cjx
 * @version: 1.0
 * @date: 2020/01/06
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