<?php
namespace App\Services\Orders;

use App\Exceptions\CommonException;

/**
 * 订单实体类
 * 实际订单操作使用此类
 * @author: yanxs <541139655@qq.com>
 * @version: 1.0
 * @date: 2019/10/22
 */
class OrdersEntity extends OrdersAbstract
{
    /**
     * 用于标准化数据格式
     * @param $data
     * @return array $data 处理后的数据
     * @throws CommonException
     */
    public function setOrdersData($data)
    {
        // TODO: Implement setOrdersData() method.
        if(!is_array($data['items']) || !is_array($data['receiver_info'])) {
            //throw new CommonException(__('exception.order_create_less_params'),'80001');
            app(\App\Services\Exception::class)->throwException('80001',__FILE__.__LINE__);
        }

        return $data;
    }



    /**
     * 额外的处理
     */
    public function extraProcess()
    {
        // TODO: Implement extraProcess() method.
    }
}