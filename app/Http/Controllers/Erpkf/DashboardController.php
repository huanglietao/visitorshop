<?php
namespace App\Http\Controllers\Erpkf;

use App\Http\Controllers\Erpkf\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * 控制台
 *
 * 展示统计及报表的相关数据
 * @author: cjx
 * @version: 1.0
 * @date: 2020/01/02
 */

class DashboardController extends BaseController
{

    /**
     * 控制台首页
     */
    public function index()
    {
        return view('erpkf.dashboard.index');
    }

    public function logout(Request $request)
    {
        $request->session()->forget('capital');
        header('Location: /login/index');
    }
}