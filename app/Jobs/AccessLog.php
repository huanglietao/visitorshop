<?php

namespace App\Jobs;

use App\Services\Common\Log\LogInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class AccessLog implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected  $data;
    /**
     * Create a new job instance.
     * @param array $data
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     * 将日志写入对应表中记录.
     * @return void
     */
    public function handle()
    {
        $logDriver =  app(LogInterface::class);
        $logDriver->setCollection(config("app.access_log_table"));
        $logDriver->record($this->data);
    }
}
