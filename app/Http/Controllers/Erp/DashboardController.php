<?php
namespace App\Http\Controllers\Erp;

use App\Http\Controllers\Erp\BaseController;
use App\Services\Outer\Erp\Api;
use App\Services\Outer\Erp\Dashboard;
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
        $dashFactory = app(Dashboard::class);

        //请求客户信息查询接口
        $result = $dashFactory->requestApi();

        //本地模拟测试数据
//        $str = '{"message": "\u6210\u529f", "code": 1, "data": {"partner_usable_money": 0.15, "partner_code": "1111119999", "partner_name": "\u8f6f\u4ef6\u6d4b\u8bd5\u4e0d\u53d1\u5370", "partner_real_name": "\u8f6f\u4ef6\u6d4b\u8bd5\u4e0d\u53d1\u5370", "partner_mobile": false, "partner_k_money_usable": 0.15, "partner_login_name": "1111119999"}}';
//        $result = json_decode($str,true);

        //问候语
        $greetData = $dashFactory->getGreet();

        //最近前7条充值记录
        $rechargeList = $dashFactory->getRechargeList(7);

        //登录信息
        $userLoginInfo = $dashFactory->getLoginInfo();

        return view('erp.dashboard.index',['greetData'=>$greetData,'data'=>$result['data'],'rechargeList'=>$rechargeList,'userInfo'=>$userLoginInfo]);
    }

    /**
     * 退出登录
     */
    public function logout(Request $request)
    {
        $request->session()->forget('capital');
        header('Location: '.config('erp.erp_url').'/login/index');
    }

}