<?php
namespace App\Services\Outer;

/**
 * 用于请求接口
 * @author: cjx
 * @version: 1.0
 * @date: 2020/06/16
 */
class CommonApi extends AbstractApi
{
    protected $isSign = false;
    protected $secretKey = ""; //生成签名的key
}
