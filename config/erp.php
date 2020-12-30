<?php
/**
 * erp 公共配置
 *
 * Created by PhpStorm.
 * Name: lietao<1013488674@qq.com>
 * Date: 2019/12/24
 */

return [
    //接口域名
   /*'interface_url'      => 'http://60.30.76.54:8069',*/
    'interface_url'      => env('ERP_INTERFACE_URL', ''),
    //客户登录
    'login'             => '/ec/account/do_login',
    //客户查询
    'search'            => '/ec/account/do_search',
    //充值
    'recharge'          => '/ec/account/do_gather',
    //对账单
    'sale_order'        => '/ec/account/do_search_sale_order_send_by_partner',
    //对账单订单
    'order'             => '/ec/account/do_search_sale_order_no_send_by_partner',
    //物流信息查询接口
    'logistic_order'    => '/ec/tripartite/do_search_tripartite_logistics_record_by_sale_order',
    //回写物流单号接口
    'logistic_record'   => '/ec/tripartite/do_express_num_tripartite_logistics_record_by_partner_number',
    //支付宝手续费
    'alipay'            => 0.006,
    //招行聚合支付手续费
    'zhjh'              => 0.004,
    //查询无需合并发货贸易订单发货信息接口
    'order_no_pick'     => '/ec/account/do_search_sale_order_no_send_by_partner',
    //erp订单推送接口
    'push_erp_order'    => '/ec/digital/get_down_file_info',
    //新建贸易订单接口
    'trade_order'       => '/ec/trade/do_create_cloud_trade_order',
    //外协发货回写
    'outer_delivery_write_back' =>  '/ec/out/do_express_num_out_sale_order_stock_move',
];