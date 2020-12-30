<?php
/**
 * Created by sass.
 * Author: LJH
 * Date: 2020/6/10
 * Time: 16:01
 */
namespace App\Services\Outer\Delivery;



/**
 * 物流信息回写公共接口
 * @author: liujh <vali12138@163.com>
 * @version: 1.0
 * @date: 2020/6/10
 */
interface DeliveryReturnInterface
{
    /**
     * Author: LJH
     * Date: 2020/6/10
     * Time: 16:44
     * @param $orderNo 淘宝订单号
     * @param $deliveryCode 物流运单号
     * @param $deliveryName 物流公司简称
     * @param $agent_id  分销商id
     * @param null $ext  其他
     * @return mixed
     */
    public function deliveryReturn($orderNo,$deliveryCode,$deliveryName,$agent_id,$agent_code,$ext=null);
}