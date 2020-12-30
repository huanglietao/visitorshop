<?php

namespace App\Http\Middleware;

use Closure;

/**
 * 登录验证中间件
 * @return session
 */

class ErpAuth
{

    public function handle($request, Closure $next)
    {
        if(!$request->session()->has('capital')){
            header('Location: /login/index');
            die;
        }
        return $next($request);
    }
}
