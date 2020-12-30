<?php

/**
 * 分销订单信息
 * @author auto create
 */
class DistributionOrderInfo
{
	
	/** 
	 * 认证的活体ID序列
	 **/
	public $biometric_seq;
	
	/** 
	 * 业务类型
	 **/
	public $biz_type;
	
	/** 
	 * 购买数量
	 **/
	public $buy_quantity;
	
	/** 
	 * 买家留言
	 **/
	public $buyer_message;
	
	/** 
	 * 买家名称
	 **/
	public $buyer_name;
	
	/** 
	 * 城市
	 **/
	public $city_name;
	
	/** 
	 * 分销商用户昵称
	 **/
	public $distributor_nick;
	
	/** 
	 * 快递公司编码
	 **/
	public $express_code;
	
	/** 
	 * 快递公司名称
	 **/
	public $express_name;
	
	/** 
	 * 快递单号
	 **/
	public $express_number;
	
	/** 
	 * 身份证件信息
	 **/
	public $id_card_info;
	
	/** 
	 * 运营商名称
	 **/
	public $isp_name;
	
	/** 
	 * 商品编号
	 **/
	public $item_id;
	
	/** 
	 * 商品属性
	 **/
	public $item_props;
	
	/** 
	 * 商品序列号
	 **/
	public $item_serial_no;
	
	/** 
	 * 商品标题
	 **/
	public $item_title;
	
	/** 
	 * ledgerAmount
	 **/
	public $ledger_amount;
	
	/** 
	 * 1-无需物流，2-供应商发货，3-分销商发货
	 **/
	public $logistics_flag;
	
	/** 
	 * 物流信息,收货人信息,姓名，电话，地址
	 **/
	public $logistics_info;
	
	/** 
	 * 1-未发货，2-已发货
	 **/
	public $logistics_status;
	
	/** 
	 * 订购状态:NOT_ORDER-未订购，ORDER_AUDIT-订购中(无订购接口，提交给供应商，线下受理中)，ON_ORDER-订购中(有订购接口，线上受理中),SUCCESS-订购成功，FAILURE-订购失败,CANCEL-订购取消
	 **/
	public $order_status;
	
	/** 
	 * 外部订单号
	 **/
	public $out_order_no;
	
	/** 
	 * 运营商合约编号
	 **/
	public $out_package_id;
	
	/** 
	 * 支付宝交易流水号
	 **/
	public $pay_order_no;
	
	/** 
	 * 手机号码
	 **/
	public $phone_no;
	
	/** 
	 * 号码最低消费
	 **/
	public $phone_no_min_consume;
	
	/** 
	 * 合约编号
	 **/
	public $plan_group_id;
	
	/** 
	 * 合约名称
	 **/
	public $plan_group_name;
	
	/** 
	 * 套餐编号
	 **/
	public $plan_id;
	
	/** 
	 * 套餐信息
	 **/
	public $plan_info;
	
	/** 
	 * 套餐名称
	 **/
	public $plan_name;
	
	/** 
	 * 发货的商品编号，如ICCID等
	 **/
	public $product_serial_no;
	
	/** 
	 * 省份
	 **/
	public $province_name;
	
	/** 
	 * 订购失败原因
	 **/
	public $reason;
	
	/** 
	 * 备注
	 **/
	public $remarks;
	
	/** 
	 * 订单状态
	 **/
	public $status;
	
	/** 
	 * 供应商用户昵称
	 **/
	public $supplier_name;
	
	/** 
	 * 淘宝交易订单创建时间
	 **/
	public $tb_order_create_time;
	
	/** 
	 * 淘宝交易订单号
	 **/
	public $tb_order_no;
	
	/** 
	 * 淘宝交易订单创建时间
	 **/
	public $tb_order_pay_time;
	
	/** 
	 * 订单价格
	 **/
	public $tb_order_price;	
}
?>