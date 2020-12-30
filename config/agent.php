<?php
/**
 * DMS模块公共配置
 *
 * Created by PhpStorm.
 * Name: cjx
 * Date: 2020/04/26
 */

return [

    //分销店铺类型
   'shop_type' =>[
       AGENT_SHOP_TYPE_AGT          =>      '分销',
       AGENT_SHOP_TYPE_TM           =>      '天猫',
       AGENT_SHOP_TYPE_TB           =>      '淘宝',
       AGENT_SHOP_TYPE_JD           =>      '京东',
       AGENT_SHOP_TYPE_ENTITY       =>      '实体店',
       AGENT_SHOP_TYPE_WORK         =>      '合作商户',
       AGENT_SHOP_TYPE_PRIVATE      =>      '自有商城',
   ],

    //作品状态
    'project_status'=>[
        WORKS_DIY_STATUS_MAKING         =>  '制作中',
        WORKS_DIY_STATUS_WAIT_CONFIRM   =>  '待确认',
        WORKS_DIY_STATUS_ORDER          =>  '已下单',
        WORKS_DIY_STATUS_DELETE         =>  '回收站',
    ],

    //PDF作品文件上传
    'works_pdf_file' => env('WORKS_PDF_FILE'), //文件保存目录
    'works_pdf_file_temp' => env('WORKS_PDF_FILE_TEMP'), //临时保存目录
    'works_file_url' => env('WORKS_FILE_URL'),
    'works_file'=>env('WORKS_FILE'),


    //淘宝订单状态
    'tb_order_status'=>[
        'WAIT_BUYER_PAY'=>'等待买家付款',
        'WAIT_SELLER_SEND_GOODS'=>'买家已付款',
        'TRADE_FINISHED'=>'交易成功',
        'WAIT_BUYER_CONFIRM_GOODS'=>'卖家已发货',
        'TRADE_BUYER_SIGNED'=>'买家已签收',
        'TRADE_CLOSED'=>'买家已退款',
        'TRADE_CLOSED_BY_TAOBAO'=>'交易关闭',
    ],
    //分销大客户店铺类型
    'key_customers'=>[AGENT_SHOP_TYPE_AGT,AGENT_SHOP_TYPE_WORK]

];