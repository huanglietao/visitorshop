<?php
namespace App\Http\Controllers\Agent;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;


/**
 * Created by PhpStorm.
 * Name: lietao
 * Date: 2019/8/7
 */

class OrdersController extends BaseController
{
    //列表展示页面
    public function index()
    {
        $setting = $this->getSetting();
        $pageLimit = $setting['default_pages_limit'];
        return view("agent.orders.index",compact('pageLimit'));
    }
    //ajax方式获取列表
    public function table(Request $request)
    {
        $htmlContents = $this->renderHtml('agent.orders._table');

        return response()->json(['status' => 200, 'html' => $htmlContents,'total' => 56]);
    }
}