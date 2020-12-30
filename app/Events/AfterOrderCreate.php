<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class AfterOrderCreate
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $orders;  //订单对象
    /**
     * Create a new event instance.
     * @params Object $orders
     *
     */
    public function __construct($orders)
    {
        $this->orders = $orders;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('after-order-create');
    }
}
