<?php

namespace App\Console;

use App\Http\Controllers\Api\Outer\ErpController;
use App\Http\Controllers\Backend\Salary\WorkerSalaryController;
use App\Services\ErrorSms;
use App\Services\Factory;
use App\Services\Logistics;
use App\Services\Orders\OrderFile;
use App\Services\Outer\Tmall;
use App\Services\SyncData;
use App\Services\Works\Sync;
use App\Services\Works\TbOuter;
use App\Services\Outer\Erp\OuterOrderCreate;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Services\Queue;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();

        $schedule->call(function(){  //跑同步队列生成订单
            app()->instance('sys_id','Agent');
            app()->instance('modules','orders');
            try{
                app(Sync::class)->runReadySyncQueue();
            }catch (\Exception $e){
                $str = date('Y-m-d H:i:s').':'.'同步队列生成订单发生异常错误,错误为'.$e->getMessage().',错误发生在：'.$e->getFile().',第'.$e->getLine().'行'."\n";
                file_put_contents('/data/saas/src/storage/logs/kernel_error/saas_kernel_error.log',$str,FILE_APPEND);
                /*file_put_contents('/tmp/saas_kernel_error.log',$str,FILE_APPEND);*/
                app(ErrorSms::class)->sendQueueError('同步生成订单队列');
            }
        })->everyMinute();
        $schedule->call(function(){  //跑创建订单错误的队列重新生成订单
            app()->instance('sys_id','Agent');
            app()->instance('modules','orders');
            try{
                app(Sync::class)->runErrorSyncQueue();
            }catch (\Exception $e){
                $str = date('Y-m-d H:i:s').':'.'同步队列生成订单发生异常错误,错误为'.$e->getMessage().',错误发生在：'.$e->getFile().',第'.$e->getLine().'行'."\n";
                file_put_contents('/data/saas/src/storage/logs/kernel_error/saas_kernel_error.log',$str,FILE_APPEND);
                app(ErrorSms::class)->sendQueueError('同步生成订单队列');
            }
        })->everyThirtyMinutes();
        $schedule->call(function(){  //跑推送erp订单队列
        app()->instance('sys_id','Agent');
        app()->instance('modules','orders');
            try{
                app(Factory::class)->runReadyPushErpOrderQueue();
            }catch (\Exception $e){
                $str = date('Y-m-d H:i:s').':'.'推送erp订单队列发生异常错误,错误为'.$e->getMessage().',错误发生在：'.$e->getFile().',第'.$e->getLine().'行'."\n";
                file_put_contents('/data/saas/src/storage/logs/kernel_error/saas_kernel_error.log',$str,FILE_APPEND);
                app(ErrorSms::class)->sendQueueError('推送erp订单队列');
            }
        })->everyMinute();
        $schedule->call(function(){  //跑物流信息回写队列回写信息到淘宝
            app()->instance('sys_id','Agent');
            app()->instance('modules','orders');
            try{
                app(Logistics::class)->runReadyDeliveryQueue();
            }catch (\Exception $e){
                $str = date('Y-m-d H:i:s').':'.'物流回写淘宝队列发生异常错误,错误为'.$e->getMessage().',错误发生在：'.$e->getFile().',第'.$e->getLine().'行'."\n";
                file_put_contents('/data/saas/src/storage/logs/kernel_error/saas_kernel_error.log',$str,FILE_APPEND);
                app(ErrorSms::class)->sendQueueError('物流回写淘宝队列');
            }
        })->everyMinute();
//         $schedule->call(function(){  //跑获取淘宝订单图片接口队列
//            app()->instance('sys_id','Agent');
//            app()->instance('modules','orders');
//            app(Tmall::class)->syncOrderImages();
//        })->everyMinute();
        $schedule->call(function(){  //外协创建订单队列
            app()->instance('sys_id','api');
            app()->instance('modules','order');
            try{
                app(OuterOrderCreate::class)->outerReadyErpOrderQueue();
            }catch (\Exception $e){
                $str = date('Y-m-d H:i:s').':'.'外协创建订单队列发生异常错误,错误为'.$e->getMessage().',错误发生在：'.$e->getFile().',第'.$e->getLine().'行'."\n";
                file_put_contents('/data/saas/src/storage/logs/kernel_error/saas_kernel_error.log',$str,FILE_APPEND);
                app(ErrorSms::class)->sendQueueError('外协创建订单队列');
            }
        })->everyMinute();
        /*$schedule->call(function(){  //跑生成作品的图片队列
            app()->instance('sys_id','Agent');
            app()->instance('modules','works');
            app(TbOuter::class)->runCreateWorksPic();
        })->everyMinute();*/
        $schedule->call(function(){  //计算薪酬的方法
            app()->instance('sys_id','Agent');
            app()->instance('modules','sys');
            try{
                app(WorkerSalaryController::class)->salaryCalculation();
            }catch (\Exception $e){
                $str = date('Y-m-d H:i:s').':'.'计算薪酬队列发生异常错误,错误为'.$e->getMessage().',错误发生在：'.$e->getFile().',第'.$e->getLine().'行'."\n";
                file_put_contents('/data/saas/src/storage/logs/kernel_error/saas_kernel_error.log',$str,FILE_APPEND);
                app(ErrorSms::class)->sendQueueError('计算薪酬队列');
            }
        })->everyMinute();
        $schedule->call(function(){  //跑订单发货后归档队列
            app()->instance('sys_id','Agent');
            app()->instance('modules','orders');
            try{
                app(OrderFile::class)->runReadyOrderFileQueue();
            }catch (\Exception $e){
                $str = date('Y-m-d H:i:s').':'.'订单发货归档队列发生异常错误,错误为'.$e->getMessage().',错误发生在：'.$e->getFile().',第'.$e->getLine().'行'."\n";
                file_put_contents('/data/saas/src/storage/logs/kernel_error/saas_kernel_error.log',$str,FILE_APPEND);
                app(ErrorSms::class)->sendQueueError('订单发货归档队列');
            }
        })->everyMinute();

        //自动提交生产
        $schedule->call(function(){
            app()->instance('sys_id','Merchant');
            app()->instance('modules','sys');
            try{
                app(Queue::class)->autoProduce();
            }catch (\Exception $e){
                $str = date('Y-m-d H:i:s').':'.'自动提交生产队列发生异常错误,错误为'.$e->getMessage().',错误发生在：'.$e->getFile().',第'.$e->getLine().'行'."\n";
                file_put_contents('/data/saas/src/storage/logs/kernel_error/saas_kernel_error.log',$str,FILE_APPEND);
                app(ErrorSms::class)->sendQueueError('自动提交生产队列');
            }

        })->everyMinute();

        //冲印图片下载、上传OSS
//        $schedule->call(function(){
//            app()->instance('sys_id','Agent');
//            app()->instance('modules','sys');
//            app(Tmall::class)->developingPictures();
//        });

        //淘宝天猫特殊订单归档队列
        $schedule->call(function(){
            app()->instance('sys_id','Merchant');
            app()->instance('modules','sys');
            try{
                app(OrderFile::class)->specialOrderFile();
            }catch (\Exception $e){
                $str = date('Y-m-d H:i:s').':'.'天猫特殊订单归档队列发生异常错误,错误为'.$e->getMessage().',错误发生在：'.$e->getFile().',第'.$e->getLine().'行'."\n";
                file_put_contents('/data/saas/src/storage/logs/kernel_error/saas_kernel_error.log',$str,FILE_APPEND);
                app(ErrorSms::class)->sendQueueError('天猫特殊订单归档队列');
            }
        })->hourly(1);

        //物流成本更新队列
        $schedule->call(function(){
            app()->instance('sys_id','Merchant');
            app()->instance('modules','sys');
            try{
                app(Queue::class)->updateDeliveryCost();
            }catch (\Exception $e){
                $str = date('Y-m-d H:i:s').':'.'物流成本更新队列发生异常错误,错误为'.$e->getMessage().',错误发生在：'.$e->getFile().',第'.$e->getLine().'行'."\n";
                file_put_contents('/data/saas/src/storage/logs/kernel_error/saas_kernel_error.log',$str,FILE_APPEND);
                app(ErrorSms::class)->sendQueueError('物流成本更新队列');
            }
        })->everyMinute();

         $schedule->call(function(){  //同步旧系统中淘宝订阅消息的数据
            app(SyncData::class)->syncTbTcmMsg();
        })->everyMinute();

        $schedule->call(function(){  //消息队列同步
            app(Sync::class)->messageQueueSync();
        })->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
