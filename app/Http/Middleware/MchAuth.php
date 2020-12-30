<?php

namespace App\Http\Middleware;

use Closure;

class MchAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if(!$request->session()->has('admin')){
            header('Location: /login/index');
            die;
        }
        return $next($request);
    }
}
