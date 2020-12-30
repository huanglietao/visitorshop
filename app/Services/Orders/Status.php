<?php
namespace App\Services\Orders;

use App\Repositories\SaasOrdersRepository;

/**
 *
 * 订单状态相关功能
 * 订单状态变化引起的一系列操作
 * @author: yanxs <541139655@qq.com>
 * @version: 1.0
 * @date: 2020/4/23
 */
class Status
{
    public $repoOrder;

    public function __construct(SaasOrdersRepository $order)
    {
        $this->repoOrder = $order;
    }

    /**
     * 更新订单状态为已确认
     * @param $orderId
     */
    public function updateToConfirmed($orderId)
    {
        $data = [
            'order_comf_status' => ORDER_CONFIRMED,
            'order_status'      => ORDER_STATUS_WAIT_PAY
        ];
        return $this->repoOrder->update(['order_id' => $orderId], $data);
    }

    /**
     * 更新订单状态为已付款
     * @param $orderId
     */
    public function updateToPayed($orderId)
    {
        $data = [
            'order_comf_status' => ORDER_CONFIRMED,
            'order_pay_status'  => ORDER_PAYED,
            'order_status'      => ORDER_STATUS_WAIT_PRODUCE,
        ];
        return $this->repoOrder->update(['order_id' => $orderId], $data);
    }

    /**
     * 更新订单状态为生产中
     * @param $orderId
     */
    public function updateToProducing($orderId)
    {
        $data = [
            'order_comf_status' => ORDER_CONFIRMED,
            'order_pay_status'  => ORDER_PAYED,
            'order_prod_status' => ORDER_PRODUCING,
            'order_status'      => ORDER_STATUS_WAIT_DELIVERY, //等待发货
        ];
        return $this->repoOrder->update(['order_id' => $orderId], $data);
    }

    /**
     * 更新订单状态为已发货
     * @param $orderId
     */
    public function updateToDelivery($orderId)
    {
        $data = [
            'order_comf_status'     => ORDER_CONFIRMED,
            'order_pay_status'      => ORDER_PAYED,
            'order_prod_status'     => ORDER_PRODUCED,
            'order_shipping_status' => ORDER_SHIPPED, //等待收货
            'order_status'          => ORDER_STATUS_WAIT_RECEIVE,
        ];
        return $this->repoOrder->update(['order_id' => $orderId], $data);
    }

    /**
     * 更新订单状态为已完成
     * @param $orderId
     */
    public function updateToFinish($orderId)
    {
        $data = [
            'order_comf_status'     => ORDER_CONFIRMED,
            'order_pay_status'      => ORDER_PAYED,
            'order_prod_status'     => ORDER_PRODUCED,
            'order_shipping_status' => ORDER_RECEIVED, //已收货
            'order_status'          => ORDER_STATUS_FINISH,
        ];
        return $this->repoOrder->update(['order_id' => $orderId], $data);
    }

    /**
     * 更新订单状态为售后
     * @param $orderId
     */
    public function updateToAfterSale($orderId)
    {
        $data = [
            'order_status' => ORDER_STATUS_AFTERSALE
        ];
        return $this->repoOrder->update(['order_id' => $orderId], $data);
    }

    /**
     * 撤销生产状态
     * @param $orderId
     */
    public function updateToWaitProduce($orderId)
    {
        $data = [
            'order_status'              =>  ORDER_STATUS_WAIT_PRODUCE,  //待生产  已付款
            'order_prod_status'         =>  ORDER_NO_PRODUCE,           //未生产
            'order_comf_status'         =>  ORDER_CONFIRMED,            //已确认
            'order_pay_status'          =>  ORDER_PAYED,                //已付款
            'order_shipping_status'     =>  ORDER_UNSHIPPED,            //未发货
        ];

        return $this->repoOrder->update(['order_id' => $orderId], $data);
    }
}
