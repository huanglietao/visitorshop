<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        //
        'capital/alipayreturn',
        'capital/alipaynotify',
        'cmb/notify/',
        'http://sapi.haoin.com.cn/*',
        'http://api.my.com/*',
        'http://sapi.meiin.com/*',
        'http://fxmy_api.meiin.com/*',
        'http://fxag_api.meiin.com/*',
        'finance/recharge/alipaynotify',
        'finance/recharge/alipayreturn',
        'finance/recharge/wxpaynotify',
        'finance/recharge/ajax_check_recharge'
    ];
}
