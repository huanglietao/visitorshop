<?php
/**
 * 异常/问题配置
 *
 * 所有的异常及问题配置
 * @author: yanxs <541139655@qq.com>
 * @version: 1.0
 * @date: 2019/8/21
 */

return [
    /****系统级及公共配置模块(sys)  code => 语言包  start****/

    //无明显特征的公共错误 100xx
    '10002'   =>  "common.sign_no_exists",
    '10003'   =>  "common.sign_no_match",
    '10004'   => '合成服务器未配置',

    '10010'   =>   "不存在记录",
    '10020'  =>    "批量插入出错",
    '10022'  =>    "参数不能为空",
    '10023'  =>    "缺少必要参数",

    /****系统级及公共配置模块(sys)  code => 语言包  end****/

    //-----------------分隔线------------------------------

    /****权限/商户/管理员账号(auth)  code => 语言包  start****/
    '20001'   =>   "auth.username_password_error",
    '20002'   =>   "管理员未登录",
    '20003'   =>   "账号或密码错误",
    '20030'   =>   "该商户不存在",
    '20031'   =>   "请先登录",
    /****管理员账号(auth)  code => 语言包  start****/

    //-----------------分隔线------------------------------

    /****会员管理(member)  code => 语言包  start****/
    '30001'  => '',
    /****权限/会员管理(member)  code => 语言包  end****/

    //-----------------分隔线------------------------------

    /****商品管理(goods)  code => 语言包  start****/
    '40001'  => "exception.goods_list_failed",
    '40010'  => "exception.goods_is_invalid",
    '40011'  => 'exception.goods_works_page_less',
    '40012'  => 'exception.goods_price_undefined',
    '40013'  => '当前sku不存在',
    '40014'  => '当前商品不存在',
    '40015'  => '商品规格不存在',
    '40016'  => '商品页数不得小于设定的起始P数',
    '40017'  => 'cms中未找到源商品,商户自定义商品不能同步配置',
    /****商品管理(template)  code => 语言包  end****/

    //-----------------分隔线------------------------------

    /****模板管理(template)  code => 语言包  start****/
    '50001'  => '',
    '50030'  => '获取素材分类出错,记录不存在',
    '50031'  => '获取模板接口:模板id必须',
    '50032'  => '模板分类不存在',
    '50033'  => '模板子页id不存在',
    '50034'  => '保存模板失败:模板子页id不存在',
    '50035'  => '保存模板失败:子页数据不能为空',
    '50036'  => '保存模板预览图失败',
    '50037'  => '更新模板预览图失败',
    '50038'  => '更新布局数据失败',
    '50039'  => '对应模板记录不存在',
    '50010'  => '模板复制失败',
    '50011'  => '原模板不存在',
    '50012'  => '克隆失败',
    '50013'  => '布局复制失败',
    '50014'  => '原模板布局不存在',
    /****模板管理(template)  code => 语言包  end****/

    //-----------------分隔线------------------------------

    /****作品管理(works)  code => 语言包  start****/
    '60001'  => '作品记录不存在',

    '60030' =>  '作品保存失败:无对应模板',
    '60031' =>  '作品保存失败:子页数据格式错误',
    '60032' =>  '作品保存失败:无对应货品数据',
    '60033' =>  '作品保存失败:保存作品子页失败',
    '60034' =>  '作品保存失败:保存作品舞台数据失败',
    '60035' =>  '作品保存失败:分销商id必须',
    '60036' =>  '作品保存失败:商户id必须',
    '60037' =>  '作品无子页数据',
    '60038' =>  '订单号不存在',
    '60039' =>  '该订单作品数量已达到最大值,请先删除原有作品再进行制作',
    '60040' =>  '货号异常，请确认货号是否存在或者该商品类目是否存在',
    '60041' =>  '作品状态更新失败',
    '60042' =>  '作品记录创建失败',
    '60043' =>  '作品保存失败',
    '60044' =>  '数量超过商品所能做的作品数量,请调整制作数量或先删除原有作品再进行制作',
    '60045' =>  '该作品p数异常,无法制作',
    '60046' =>  '该商品作品p数错误，无法制作',
    '60047' =>  '该冲印商品此p数的制作数量已达到最大值，无法制作',
    '60048' =>  '该作品已保存，无需再重复提交保存',
    /****作品管理(works)  code => 语言包  end****/

    //-----------------分隔线------------------------------

    /****订单管理(orders)  code => 语言包  start****/
    '70001'  => 'exception.order_create_less_params',
    '70002'  => 'exception.order_create_less_params',
    '70003'  => 'exception.order_create_less_params',
    '70004'  => 'exception.order_create_less_params',
    '70005'  => 'exception.order_create_less_params',
    '70006'  => 'exception.order_create_less_params',
    '70007'  => 'exception.order_create_less_params',
    '70008'  => 'exception.order_create_less_params',
    '70009'  => 'exception.order_create_less_params',
    '70010'  => 'exception.order_create_less_params',
    '70011'  => 'exception.order_create_less_params',
    '70012'  => 'exception.order_create_less_params',
    '70013'  => 'exception.order_create_less_params',
    '70014'  => 'exception.order_create_chanel_empty',
    '70015'  => 'exception.order_create_mch_empty',
    '70016'  => 'exception.order_create_user_empty',
    '70018'  => 'exception.order_create_file_empty',
    '70019'  => 'exception.order_create_insert_file_failed',
    '70020'  => 'exception.order_create_order_repeat',
    '70021'  => 'exception.order_create_works_empty',
    '70023'  => 'exception.order_create_ship_fee_error',
    '70024'  => 'exception.order_create_goods_price_error',
    '70025'  => 'exception.order_create_total_amount_error',
    '70026'  => 'exception.order_create_insert_data_error',
    '70027'  => 'exception.order_create_insert_item_error',
    '70028'  => 'exception.order_create_balance_pay_error',

    '70030'  => 'exception.order_record_exist', //该订单记录不存在
    '70031'  => 'exception.order_operate_cancel', //订单已提交生产，不可取消
    '70032'  => 'exception.order_operate_distribution', //订单未满足配货条件
    '70033'  => 'exception.order_operate_input_delivery', //请填写物流单号
    '70034'  => 'exception.order_operate_delivery_unsatisfied', //订单未满足发货条件
    '70035'  => 'exception.order_operate_exist_delivery', //物流单号已存在，请重新输入
    '70036'  => 'exception.order_operate_delivery_fail', //订单发货失败
    '70037'  => 'exception.order_operate_exchange_fail', //订单未满足换货条件
    '70038'  => 'exception.order_operate_create_exchange_fail', //创建换货单失败
    '70039'  => 'exception.order_operate_refund_money_exceed', //退款金额不能超过订单金额
    '70040'  => 'exception.order_operate_exchange_exist', //该订单已有换货单
    '70041'  => 'exception.order_operate_job_create_fail', //售后工单创建出错
    '70042'  => 'exception.order_operate_job_exist', //该订单已创建过售后工单
    '70043'  => 'exception.order_operate_confirm_receiver_fail', //确认收货失败
    '70044'  => 'exception.order_operate_job_fail', //订单未满足售后条件
    '70045'  => 'exception.order_operate_job_withdraw_fail', //售后单撤回失败
    '70046'  => 'exception.order_operate_job_not_exist', //该售后单记录不存在
    '70047'  => 'exception.order_operate_not_withdraw', //该售后单不可撤回
    '70048'  => 'exception.order_operate_tag_empty', //请选择订单标签
    '70049'  => 'exception.order_operate_tag_error', //订单标签设置出错

    '70050'  => 'exception.order_create_balance_pay_error', //创建订单:余额支付订单出错
    '70051'  => 'exception.order_create_balance_less',  //创建订单:账户余额不足

    '70070'  => 'exception.order_operate_submit_not', //订单状态未满足提交生产条件
    '70071'  => 'exception.order_operate_submit_exist', //该订单已提交生产，请勿重复提交
    '70072'  => 'exception.order_operate_work_not_synthesized', //作品尚未合成,请稍后再试
    '70073'  => 'exception.order_operate_submit_error', //订单提交生产出错
    '70074'  => 'exception.order_operate_check_error', //作品文件出错了
    '70075'  => 'exception.order_operate_file_download_fail', //文件未处理完成，无法下载
    '70076'  => 'exception.order_operate_file_download_error', //文件下载出错
    '70077'  => 'exception.order_operate_auto_submit_error', //订单自动提交生产出错
    '70078'  => 'exception.order_balance_not_exist', //未配置余额支付
    '70079'  => 'exception.order_code_not_set', //未配置合作编号
    '70080'  => 'exception.order_outer_num_error', //稿件数量与商品数量不符
    '70081'  => 'exception.order_outer_sku_error', //货品ID无效
    '70082'  => 'exception.order_outer_delivery_template_error', //未找到快递模板
    '70083'  => 'exception.order_not_reviewed', //订单未审核完成
    '70084'  => 'exception.order_cancel_error', //订单取消出错
    '70085'  => 'exception.order_refund_error', //余额退款出错
    '70086'  => 'exception.order_user_not_exist', //账号不存在
    '70087'  => 'exception.order_already_cancel', //订单已被取消交易，请勿重复操作
    '70088'  => 'exception.order_service_not_update', //该售后单已被处理或归档，不可修改
    '70089'  => 'exception.order_delivery_queue_error', //发货物流队列出错
    '70090'  => 'exception.order_examine_error', //审核归档出错
    '70091'  => 'exception.oss_error', //OSS处理出错
    '70092'  => 'exception.print_data_insert_error', //冲印图片数据插入出错
    '70093'  => 'exception.factory_queue_not_exist', //推送工厂队列不存在
    '70094'  => 'exception.delivery_code_not_empty', //快递单号不能为空
    '70095'  => 'exception.delivery_type_not_empty', //物流方式不能为空
    '70096'  => 'exception.outer_order_not_exist', //外协订单不存在

    '70097'  => "exception.suppliers_no_exists",//"供应商未设置物流成本",
    '70098'  => "exception.express_no_exists",//"指定快递模板下物流成本不存在",
    '70099'  => "exception.logistics_costs_no_exists",//"未设置相应成本规则",
    '70100'  => "exception.no_orders_generated",//该订单号未生成系统订单
    '70101'  => "exception.order_no_error",//订单号不存在
    '70102'  => "exception.special_order_error",//特殊订单归档出错
    /****订单管理(orders)  code => 语言包  end****/

    //-----------------分隔线------------------------------

    /****物流管理(logistics)  code => 语言包  start****/
    '80001'  => "exception.delivery_temp_no_exists",//"快递模板不存在",
    '80002'  => "exception.delivery_no_exists",//"指定快递模板下快递不存在",
    '80003'  => "exception.delivery_price_no_exists",//"未设置相应计费规则",
    /****物流管理(logistics)  code => 语言包  end****/

    //-----------------分隔线------------------------------

    /****支付管理(payment)  code => 语言包  start****/
    '90001'  => '',
    /****支付管理(payment)  code => 语言包  end****/

    //-----------------分隔线------------------------------

    /****分销管理(agent)  code => 语言包  start****/
    '11001'  => '不存在分销记录',

    '11101'  => '分销账号不存在',
    /****分销管理(agent)  code => 语言包  end****/

    //-----------------分隔线------------------------------

    /****媒体管理(media)  code => 语言包  start****/
    '12001'  => '',
    /****媒体管理(media)  code => 语言包  end****/

    //-----------------分隔线------------------------------

    /****工厂管理(supplier)  code => 语言包  start****/
    '13001'  => 'exception.file_download_error', //文件下载出错
    '13002'  => 'exception.file_download_status_error', //文件状态更新出错
    /****工厂管理(supplier)  code => 语言包  end****/

    //-----------------分隔线------------------------------

    /****营销管理(market)  code => 语言包  start****/
    '14001'  => '',
    /****营销管理(market)  code => 语言包  end****/

    //-----------------分隔线------------------------------

    /****财务统计管理(statistics)  code => 语言包  start****/
    '15001'  => 'exception.logistics_cost_queue_error',
    /****财务统计管理(statistics)  code => 语言包  end****/

    //-----------------分隔线------------------------------

    /****地址库相关管理(areas)  code => 语言包  start****/
    '16001'   => 'exception.province_name_empty',
    '16002'   => 'exception.code_not_exist', //查不到编码对应的记录
    '16003'   => 'exception.province_invalid', //无效的省份
    '16004'   => 'exception.city_invalid', //无效的城市
    '16005'   => '无效的区/县', //无效的城市
    '161001'  => '参数getType必须',
    /****地址库相关管理(areas)  code => 语言包  end****/

    /***其他(ohter)  code => 语言包  start****/
    '21001'   => '聚石塔配置信息出错',
    '21002'   => '获取淘宝top订单接口失败',
    '21003'   => '获取外部订单图片接口失败',
    '21004'   => '插入订单图片队列表失败',
    '21005'   => '插入外部订单图片表失败',
    /****其他(ohter)  code => 语言包  end****/

    /***商户控制台(console)  code => 语言包  start****/
    '22001'   => '该商户不存在',
    /****商户控制台(ohter)  code => 语言包  end****/

    /***对接Erp创建订单接口(erp)  code => 语言包  start****/
    '230001'   => '签名验证错误',
    '230002'   => '当前时间戳验证失败',
    '230003'   => '订单详情信息不能为空',
    '230004'   => '订单流水号不能为空',
    '230005'   => '工厂产品名称不能为空',
    '230006'   => '印刷份数需为数字且不能为空',
    '230007'   => '印刷文件url不能为空',
    '230008'   => '收货信息不能为空',
    '230009'   => '收货人信息不能为空',
    '230010'   => '收货人电话不能为空',
    '230011'   => '详细地址不能为空',
    '230012'   => '快递方式不能为空',
    '230013'   => '客户简称不能为空',
    '230014'   => '供货商代码不能为空',
    '230015'   => '获取不到分销商,无法创建订单,请检查客户简称是否填写正确',
    '230016'   => '获取不到对应供货商,无法创建订单,请检查供货商代码是否填写正确',
    '230017'   => '获取不到对应产品,无法创建订单,请检查产品名称是否填写正确',
    '230018'   => '解析地址出错,无法创建订单,请检查收货地址是否填写正确',
    '230019'   => '产品内页url为空,无法创建订单',
    '230020'   => '快递方式无法识别，无法创建订单,请检查快递方式是否填写正确',
    '230021'   => '创建订单失败',
    '230022'   => '该商品匹配不了该快递',
    /****对接Erp创建订单接口(erp)  code => 语言包  end****/


    /***推送订单到erp(erp)  code => 语言包  start****/
    '24001'   => '该订单不存在',
    '24002'   => '该订单由口罩与其他类订单组成，无法推送',
    /****推送订单到erp(erp)  code => 语言包  end****/

    /***薪酬计算(erp)  code => 语言包  start****/
    '25001'   => '生产人员为空，无薪酬',
    '25002'   => '总产量为0',
    '25004'   => '含有员工表没有记录的生产人员',
    '25005'   => '含有未知职位等级的员工',
    /****薪酬计算(erp)  code => 语言包  end****/


];