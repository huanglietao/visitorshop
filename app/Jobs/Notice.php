<?php

namespace App\Jobs;

use AlibabaCloud\Client\AlibabaCloud;
use App\Exceptions\CommonException;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Mail;

/**
 * 消息通知队列
 * Class Notice
 * @package App\Jobs
 */
class Notice implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected  $data;
    /**
     * Create a new job instance.
     * @param $data 通知的数据
     */
    public function __construct($data)
    {
        //
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $notice_manager = $this->data;

        //email,sms... 添加相应的通知方式只需
        //添加相应的sendxxx方法去实现即可.
        $type = isset($notice_manager['type']) ? $notice_manager['type']:'email';
        call_user_func([$this,'send'.ucfirst($type)],$notice_manager);

    }

    /**
     * 发送邮件操作
     * @param $notice_manager
     */
    private function sendEmail($notice_manager)
    {
        foreach ($notice_manager['receiver'] as $k => $v) {
            //Mail::to()
            $send_data = [
                'email' => $v['email'],
                'name'  => $notice_manager['subject']['name'],
                'title' => $notice_manager['subject']['title'],
                'contents' => var_export($notice_manager['dataDetail'],true)
            ];
            Mail::send($notice_manager['temp'], $send_data, function($message) use ($send_data) {
                $message->to($send_data['email'], $send_data['name'])->subject($send_data['title']);
            });
        }
    }

    /**
     * 发送短信通知,使用阿里去短信服务
     *
     * @param $notice_manager
     * @throws CommonException
     */
    private function sendSms($notice_manager)
    {
        AlibabaCloud::accessKeyClient(config('app.ali_access_key'),config('app.ali_access_secret'))
            ->regionId('cn-hangzhou')
            ->asDefaultClient();

        try {
            $result = AlibabaCloud::rpc()
                ->product('Dysmsapi')
                ->version('2017-05-25')  //版本号是阿里云固定的
                ->action('SendSms')
                ->method('POST')
                ->host('dysmsapi.aliyuncs.com')
                ->options($notice_manager['options'])
                ->request();
            $smsReturn = $result->toArray();
            file_put_contents('/tmp/sms.log',var_export($result,true),FILE_APPEND);
            if($smsReturn['Code'] !== 'OK') { //短信出错
                app()->instance('sys_id','sys');
                app()->instance('modules','sms');
                throw new CommonException(__("exception.sms_send_error"),10510,false,'',$smsReturn);
            }


        } catch (ClientException $e) {
            echo $e->getErrorMessage() . PHP_EOL;

        } catch (ServerException $e) {
            echo $e->getErrorMessage() . PHP_EOL;
        } catch (CommonException $e) {
            echo $e->getMessage() . PHP_EOL;
        }
    }
}
