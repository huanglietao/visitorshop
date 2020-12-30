<?php
namespace App\Http\Controllers\Erp;

use App\Http\Controllers\Erp\BaseController;

/**
 * ERP系统控制台
 *
 * 功能详细说明
 * @author: yanxs <541139655@qq.com>
 * @version: 1.0
 * @date: 2019/10/12
 */
class IndexController extends BaseController
{
    public function index()
    {
        $userinfo = session("capital");
        $allowImportOrderArr = ['0000002575','0000002576','0000002577'];

        if (in_array($userinfo['data']['partner_login_name'], $allowImportOrderArr)) {
            $allowImport = 1;
        } else {
            $allowImport = 0;
        }

        return view('erp.index',['allow_import' => $allowImport]);
    }
}