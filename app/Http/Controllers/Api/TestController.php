<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\ApiException;
use App\Services\Helper;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Encryption\Encrypter;
use Illuminate\Http\Request;

/**
 * 控制器约定，必须定义modules属性，标识当前功能属于哪一个
 * 模块。ApiException在抛出错误的同时,可选择是否会发送邮件
 * Class TestController
 * @package App\Http\Controllers\Api
 */
class TestController extends BaseController
{
    protected $modules = 'goods';
    //
    public function index()
    {

        $cookie_value = "eyJpdiI6InNRb2Y4RmdITlVVQStsQVhJdTdOQ1E9PSIsInZhbHVlIjoiS1JHbGkxZlk1c0JxcjNjN0UxMjZGeE1JVkNvMEpYMmhxQlp5d3N0MlV3aXBneVVLXC9Ic01DbVlNK0pmRFFKRVgiLCJtYWMiOiJlNWRmYzU2MzRlYThlNmViZjNjMWJjZTNkZGZlM2Y5MTBiMjM5ZTA2ZDg0Njk3ODVmYzZjMjVmNmVmY2UyODNkIn0=";

        //解密
        //var_dump(base64_decode(config("app.key")));exit;
        $CookiesEncrypt = new Encrypter(base64_decode(substr(config("app.key"), 7)), config('app.cipher'));
        $value = $CookiesEncrypt->decrypt($cookie_value,false);
        var_dump($value);exit;


        try{
            $ext_data = [
                '操作人' => 'yanxs',
                'contents'  => [
                    'say'   => '操作队列失败，请重试!'
                ]

            ];
           // var_dump($_POST);exit;
            return Helper::returnJsonSuccess(['data' => $_POST]);
        } catch (ApiException $e) {
            var_dump($e->getMessage());exit;
            Helper::returnJsonFail();
        }

    }
}
