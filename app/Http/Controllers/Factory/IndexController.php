<?php
namespace App\Http\Controllers\Factory;


/**
 * 供货商系统首页
 *
 * 功能详细说明
 * @author: cjx
 * @version: 1.0
 * @date: 2020/5/14
 */
class IndexController extends BaseController
{
    public function index()
    {
        $menuList = $this->auth->getSidebar();

        return view('factory.index',['menuList'=>$menuList,'userInfo'=>$this->userInfo]);
    }
}