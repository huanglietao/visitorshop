<?php
namespace App\Services;

use App\Jobs\Notice;

/**
 * 异常发送短信逻辑
 *
 * 异常发送短信逻辑
 * @author: hlt <1013488674@qq.com>
 * @version: 1.0
 * @date: 2020/5/6
 */
class ErrorSms
{

    /**
     * @param $areaId
     * @return array
     */
    public function sendQueueError($msg)
    {
        //判断redis中是否存在错误信息最后发送时间
        $redis = app('redis.connection');
        $lastTime = $redis->get('error_sms_time');
        if (empty($lastTime) || time()-$lastTime>3600)
        {
            //当没有发送时间或者这次异常比上一次超过一小时即可发送信息通知开发人员查看

            //所需传入的数据
            $messageData = [
                'type' => 'sms',
                'options' => [
                    'query' => [
                        'RegionId' => "cn-hangzhou",
                        'PhoneNumbers' => ERROR_PHONE,
                        'SignName' => ERROR_SIGN_NAME,
                        'TemplateCode' => ERROR_TEMPLATE_CODE,
                        'TemplateParam' => "{'mod':'".$msg."'}",
                    ]
                ]
            ];

            //发送信息
            $sms = new Notice($messageData);
            $sms->handle();
            //将这次发送的时间存进redis
            $redis->set('error_sms_time' , time());
        }
        return true;


    }
}