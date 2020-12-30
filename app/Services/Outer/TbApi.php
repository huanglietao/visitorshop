<?php
namespace App\Services\Outer;

/**
 * 针对淘宝/天猫接口的api
 * @author: yanxs <541139655@qq.com>
 * @version: 1.0
 * @date: 2019/12/25
 */
class TbApi extends AbstractApi
{
    protected $isSign = false;
    protected $secretKey = ""; //生成签名的key
}
