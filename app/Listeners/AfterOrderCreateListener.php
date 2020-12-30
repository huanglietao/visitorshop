<?php

namespace App\Listeners;

use App\Events\AfterOrderCreate;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * 订单创建后续操作的事件监听
 * Class AfterOrderCreateListener
 * @package App\Listeners
 */
class AfterOrderCreateListener
{
    /**
     * Create the event listener.
     *
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  AfterOrderCreate  $event
     * @return void
     */
    public function handle(AfterOrderCreate $event)
    {
        //
        $orders = $event->orders;
        $orders->create();
    }
}
