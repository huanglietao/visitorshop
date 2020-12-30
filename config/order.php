<?php
/**
 * 订单模块公共配置
 *
 * Created by PhpStorm.
 * Name: cjx
 * Date: 2020/04/23
 */

return [

    //售后责任认定
   'service_responsibility' =>  [
   		'生产问题：印刷问题、装订问题、裁剪问题、包装问题、工厂丢件、工厂延误',
   		'物流问题：破损，丢件',
   		'技术问题：元素移位、推送失败、图片丢失、图片破损、其他',
   		'客服问题：客服操作失误',
   		'运营问题：价格纠结',
   		'设计问题：模板问题',
   		'其他：客户纠缠不清',
   ],

    //售后处理方式
    'service_handle_type'   =>  [
        ORDER_AFTER_PREFERENTIAL    =>  '协商优惠',
        ORDER_AFTER_REFUND_MONEY    =>  '仅退款',
        ORDER_AFTER_REFUND_GOODS    =>  '退货退款',
        ORDER_AFTER_EXCHANGE        =>  '换货',
        ORDER_AFTER_OTHERS          =>  '其它',
    ],

    //售后处理状态
    'service_status'    =>  [
        ORDER_AFTER_STATUS_UNPROCESSED      =>      '未处理',
        ORDER_AFTER_STATUS_PROCESSED        =>      '已处理',
        ORDER_AFTER_STATUS_FILE             =>      '审核归档',
        ORDER_AFTER_STATUS_WITHDRAW         =>      '已撤回',
    ],

    //售后物品状态
    'service_good_status'   =>  [
        ORDER_AFTER_GOOD_STATUS_NOT_RECEIVER    =>  '未收到货',
        ORDER_AFTER_GOOD_STATUS_RECEIVER        =>  '已收到货',
    ],

    //售后类型
    'service_type'   =>  [
        ORDER_AFTER_TYPE_REFUND             =>  '仅退款',
        ORDER_AFTER_TYPE_GOOD_REFUND        =>  '退货退款',
    ],

    //售后申请原因
    'service_reason'    =>  [
        '生产问题：印刷问题、装订问题、裁剪问题、包装问题、工厂丢件、工厂延误',
        '物流问题：破损，丢件',
        '技术问题：元素移位、推送失败、图片丢失、图片破损、其他',
        '客服问题：客服操作失误',
        '运营问题：价格纠结',
        '设计问题：模板问题',
        '其他：客户纠缠不清',
    ],

    //提交生产逻辑处理标识
    'online_create_root'=>'E:\\FM-FILES\\',
    'online_create_root_d'=>'D:\\FM-FILES\\',
    'online_create_dir' => "FM-FILES",
    'cover_flag'=>'_cover',
    'entity_flag'=>'.txt',

    //N8订单相关
    'zt_n8_sp_id' => '30101',
    'zt_n8_secret' => '0Ai9ALYycMURfiUxMrBaQTQr5Rg7qLGK',

    //供货商订单状态
    'supplier_order_status' =>  [
        SP_ORDER_STATUS_PRODUCE     =>  '待生产',
        SP_ORDER_STATUS_PRODUCING   =>  '生产中',
        SP_ORDER_STATUS_PRINT       =>  '已打码',
        SP_ORDER_STATUS_DELIVERY    =>  '已送货',
        SP_ORDER_STATUS_SEND        =>  '已发货',
    ],
    //同步队列的商品类型
    'big_type' => [
        SYNC_QUEUE_DIY     => '定制印品',
        SYNC_QUEUE_ENTITY  => '实物',
        SYNC_QUEUE_MIX     => '混合',
    ],
];