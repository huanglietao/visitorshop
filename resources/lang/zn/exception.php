<?php
/**
 * 异常语言包
 *
 * 系统抛出异常所用语言包，异常语言包规定每一条信息最多只能有一个参数,
 * 如果处理异常方法只传了一个参数而bus和dev都有key,那么key都替换成这个参数
 * @author: yanxs <541139655@qq.com>
 * @version: 1.0
 * @date: 2019/10/21
 */
return [
    "sms_send_error"                        => "发送短信出错",


    /****商品相关(goods)  start****/
    "goods_list_failed"  => [
        'bus'                               => "获取商品[:key]列表失败",
        'dev'                               => "商品id[:key]不存在",  //异常语言包,参数必须使用key字段,不然异常处理环境不做解析
    ],
    'goods_is_invalid'                      => "商品不存在或已下架",
    'goods_works_page_less'                 => '商品对应作品页数有误',
    'goods_price_undefined'                 => '商品价格未定义',
    /****商品相关(goods)  end****/

    /****订单相关(orders)  start****/
    "order_create_less_params"          => [
        'bus'                               => "创建订单:缺少必要参数",
        'dev'                               => "创建订单:参数不能为空",  //异常语言包,参数必须使用key字段,不然异常处理环境不做解析
    ],
    'order_create_chanel_empty'             => '创建订单:传入渠道参数无效',
    'order_create_mch_empty'                => '创建订单:传入商户参数无效',
    'order_create_user_empty'               => '创建订单:传入会员参数无效',
    'order_create_file_empty'               => '创建订单:稿件信息必须',
    'order_create_insert_file_failed'       => '创建订单:插入搞件信息失败',
    'order_create_order_repeat'             => '创建订单:订单号重复',
    'order_create_works_empty'              => '创建订单:获取作品信息失败',
    'order_create_ship_fee_error'           => '创建订单:传入运费参数错误',
    'order_create_goods_price_error'        => '创建订单:传入商品价格参数错误',
    'order_create_total_amount_error'       => '创建订单:传入订单总价错误',
    'order_create_insert_data_error'        => '创建订单:保存订单数据出错',
    'order_create_insert_item_error'        => '创建订单:保存订单详情数据出错',
    'order_create_balance_pay_error'        => '创建订单:余额支付订单出错',
    'order_create_balance_less'             => '创建订单:账户余额不足',
    'order_record_exist'                    => '该订单记录不存在',
    'order_operate_cancel'                  => '订单已提交生产，不可取消',
    'order_operate_distribution'            => '订单未满足配货条件',
    'order_operate_input_delivery'          => '请填写物流单号',
    'order_operate_delivery_unsatisfied'    => '订单未满足发货条件',
    'order_operate_exist_delivery'          => '物流单号已存在，请重新输入',
    'order_operate_delivery_fail'           => '订单发货失败',
    'order_operate_exchange_fail'           => '订单未满足换货条件',
    'order_operate_create_exchange_fail'    => '创建换货单失败',
    'order_operate_refund_money_exceed'     => '退款金额不能超过订单金额',
    'order_operate_exchange_exist'          => '创建失败,该订单已有换货单',
    'order_operate_job_create_fail'         => '售后工单创建出错',
    'order_operate_job_exist'               => '该订单已创建过售后工单',
    'order_operate_job_fail'                => '订单未满足售后条件',
    'order_operate_confirm_receiver_fail'   => '确认收货失败',
    'order_operate_job_withdraw_fail'       => '售后单撤回失败',
    'order_operate_job_not_exist'           => '该售后单记录不存在',
    'order_operate_not_withdraw'            => '该售后单不可撤回',
    'order_operate_tag_empty'               => '请选择订单标签',
    'order_operate_tag_error'               => '订单标签设置出错',
    'order_operate_submit_not'              => '订单状态未满足提交生产条件',
    'order_operate_submit_exist'            => '该订单已提交生产，请勿重复提交',
    'order_operate_work_not_synthesized'    => '作品尚未合成,请稍后再试',
    'order_operate_submit_error'            => '订单提交生产出错',
    'order_operate_check_error'             => '作品文件出错了',
    'order_operate_file_download_fail'      => '文件未处理完成，无法下载',
    'order_operate_file_download_error'     => '文件下载出错',
    'order_operate_auto_submit_error'       => '订单自动提交生产出错',
    'order_balance_not_exist'               => '未配置余额支付',
    'order_code_not_set'                    => '未配置合作编号',
    'order_outer_num_error'                 => '稿件数量与商品数量不符',
    'order_outer_sku_error'                 => '货品ID无效',
    'order_outer_delivery_template_error'   => '未找到快递模板',
    'order_not_reviewed'                    => '订单未审核完成',
    'order_cancel_error'                    => '订单取消出错',
    'order_refund_error'                    => '余额退款出错',
    'order_user_not_exist'                  => '账号不存在',
    'order_already_cancel'                  => '订单已被取消交易，请勿重复操作',
    'order_service_not_update'              => '该售后单已被处理或归档，不可修改',
    'order_delivery_queue_error'            => '发货物流队列出错',
    'order_examine_error'                   => '审核归档出错',
    'oss_error'                             => 'OSS处理出错',
    'print_data_insert_error'               => '冲印图片数据插入出错',
    'factory_queue_not_exist'               => '推送工厂队列不存在',
    'delivery_code_not_empty'               => '快递单号不能为空',
    'delivery_type_not_empty'               => '物流方式不能为空',
    'outer_order_not_exist'                 => '外协订单不存在',
    "suppliers_no_exists"                   => '供应商未设置物流成本',
    "express_no_exists"                     => '指定快递模板下物流成本不存在',
    "logistics_costs_no_exists"             => '未设置相应物流成本规则',
    "no_orders_generated"                   => '该订单号未生成系统订单',
    "order_no_error"                        => '订单号不存在',
    "special_order_error"                   => '特殊订单归档出错',

    /****订单相关(orders)  end****/

    /****地址库管理(areas)  start****/
    "province_name_empty"                   => '省份名称不能为空',
    "code_not_exist"                        => '查不到编码对应的记录',
    "province_invalid"                      => '无效的省份',
    "city_invalid"                          => '无效的城市',

    /****地址库管理(areas)  end****/

    /****快递物流相关(logistics)  start****/
    "delivery_temp_no_exists"               => '快递模板不存在',
    'delivery_no_exists'                    => '指定快递模板下无快递方式',
    'delivery_price_no_exists'              => '运送方式未设置计费规则',

    /****快递物流相关(logistics)  end****/

    /****工厂管理(supplier)  start****/
    "file_download_error"                   => '文件下载出错',
    "file_download_status_error"            => '文件状态更新出错',

    /****工厂管理(supplier)  end****/

    /****财务统计管理(statistics)  start****/
    "logistics_cost_queue_error"          => '物流成本更新出错',

    /****财务统计管理(statistics)  end****/


];