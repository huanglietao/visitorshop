<?php
namespace App\Http\Controllers;

/**
 * 测试控制器
 *
 * 测试控制器
 * @author: yanxs <541139655@qq.com>
 * @version: 1.0
 * @date: 2019/7/24
 */

class TestController extends Controller
{
    public function index()
    {
        return view('admin.index');
    }
    public function hlt()
    {
        return view('admin.hlt');
    }
    public function tips()
    {
        return view('admin.tips');
    }
}