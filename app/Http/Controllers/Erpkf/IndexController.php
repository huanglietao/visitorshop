<?php
namespace App\Http\Controllers\Erpkf;

use App\Http\Controllers\Erpkf\BaseController;

/**
 * ERPKF系统控制台
 *
 * 功能详细说明
 * @author: cjx
 * @version: 1.0
 * @date: 2020/01/06
 */
class IndexController extends BaseController
{
    public function index()
    {
        return view('erpkf.index');
    }
}