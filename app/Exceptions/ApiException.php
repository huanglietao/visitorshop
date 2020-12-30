<?php
/**
 * api异常处理
 *
 * 处理运行中的异常并按实际情况确定是否记录日志
 * @author: yanxs <541139655@qq.com>
 * @version: 1.0
 * @date: 2019/8/21
 */

namespace App\Exceptions;


use Exception;

class ApiException extends CommonException
{
    /**
     * 自定义异常处理
     * @param Exception $exception
     */
    public function customerLog(Exception $exception)
    {
        //记系统业务相关的日志

        //发送邮件信息
    }

}