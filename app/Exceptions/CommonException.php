<?php
/**
 * 异常处理类
 *
 * 通用异常处理及相关通知
 * @author: yanxs <541139655@qq.com>
 * @version: 1.0
 * @date: 2019/8/21
 */

namespace App\Exceptions;


use App\Jobs\Notice;
use App\Services\Common\Log\LogInterface;
use Exception;
use Mail;

class CommonException extends Exception
{
    /**
     * 重写异常处理的类
     * CommonException constructor.
     * @param string $message  //通知信息
     * @param int $code
     * @param array $ext_info 在邮件通知时生效,可记录运行时数据.
     * @param bool $is_notice 是否发邮件通知
     * @param string $notice_who 通知人 /dev(开发人员)/bus(业务人员)/all(全部)
     */
    public function __construct($message, $code = 0,  $is_notice = false, $notice_who='all', $ext_info=null)
    {
        //正常抛出运营前端(bus)的信息
        $arrExceptionMessage = explode('||', $message);
        if(count($arrExceptionMessage) == 1) {
            $busMessage = $devMessage = $arrExceptionMessage[0];
        } else {
            $busMessage = $arrExceptionMessage[0];
            $devMessage = $arrExceptionMessage[1];
        }

        parent::__construct($busMessage, $code);

        //定义日志记录的内容
        $logData = [
            'sys'     => app('sys_id')??'cli',
            'modules' => app('modules')??'sys',
            'line'    => \Config::get('exception_pos'),
            'code'    => $code,
            'addtime' => time(),
            'ip'      => \Request::getClientIp(),
            'message' => $message,
            'data'    => $ext_info
        ];
        $this->recordLog($logData, $is_notice);
    }



    /**
     * 记录异常日志并提醒
     * @param null $data      日志数据
     * @param bool $is_notice 是否发送通知
     * @param string $notice_who 通知人 /dev(开发人员)/bus(业务人员)/all(全部)
     * @return mixed
     */
    public function recordLog($data=null, $is_notice=false, $notice_who='all')
    {
        if (empty($data)) {
            return true;
        }
        //写入日志
        app(LogInterface::class)->record($data);

        //获取相关负责人电话及
        $managers = config('common.manager');
        $sysNames = config('common.sys_name');
        $manager = isset($managers[$data['modules']]) ? $managers[$data['modules']] : '';
        $sysName = isset($sysNames[$data['sys']]) ? $sysNames[$data['sys']] : '';


        if ($is_notice) {
            $noticeManager = $notice_who == 'all' ? $manager : [$manager[$notice_who]];
            //发短信、发邮件
            if(config('app.env') == 'production') {
                //投递到消息队列的数据
                $messageData = [
                    'type'       => 'email',  //email,msm....
                    'receiver'   => $noticeManager,  //接收人信息
                    'temp'       => 'common.email',    //信息模板
                    'dataDetail' => $data         //信息详情
                ];

                $ret = Notice::dispatch($messageData)->onQueue('q1');
            } else {
                //投递到消息队列的数据
                $messageData = [
                    'type'       => 'email',  //email,msm....
                    'subject'    => [  //邮件显示的相关信息
                        'name'  => '你的系统有一个错误信息!',
                        'title' => '在'.$data['modules'].'模块下产生了一条程序错误信息！',
                    ],
                    'receiver'   => $noticeManager,  //接收人信息
                    'temp'       => 'common.email',    //信息模板
                    'dataDetail' => $data         //信息详情
                ];

                $ret = Notice::dispatch($messageData)->onQueue('q1');
                var_dump($ret);exit;
            }
        }
    }

    /**
     * 通过错误码获取模块
     * @param $code 错误码
     * @return string
     */
    protected function getModulesByCode($code)
    {
        return true;
    }
}