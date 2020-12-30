<?php
namespace App\Services\Common\Log;
/**
 * 日志接口定义
 *
 * 功能详细说明
 * @author: yanxs <541139655@qq.com>
 * @version: 1.0
 * @date: 2019/8/22
 */
interface LogInterface
{
    public function record($data);
}