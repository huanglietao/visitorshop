<?php

namespace App\Http\Middleware;

use App\Services\Helper;
use Closure;

class ApiAuth
{
    protected $isSign = false;
    /**
     * Handle an incoming request.
     * 使用签名验证
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $params = $request->all();
        if($this->isSign) {
            if(empty($params['sign'])) {
                $errCode = '10002';
                 return response()->json(
                     [
                         'success'  => 'false',
                         'err_code' => $errCode,
                         'err_msg' => __(config("exception")[$errCode])
                     ]
                 );
            } else {
                $sign = Helper::getSign($params);
                if($params['sign'] != $sign) {
                    return response()->json(
                        [
                            'success'  => 'false',
                            'err_code' => '10003',
                            'err_msg' => __(config("exception")['10003'])
                        ]
                    );
                }
            }
        }
        return $next($request);
    }
}
