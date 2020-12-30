<?php

/**
 * 供应商处理订单结果反馈参数
 * @author auto create
 */
class SupplierOrderResultModel
{
	
	/** 
	 * 业务类型：7-合约机分销、
	 **/
	public $biz_type;
	
	/** 
	 * 结果码
	 **/
	public $code;
	
	/** 
	 * 结果描述
	 **/
	public $desc;
	
	/** 
	 * 交易订单号
	 **/
	public $order_no;
	
	/** 
	 * 供应商外部订单号
	 **/
	public $out_order_no;
	
	/** 
	 * 订购结果状态
	 **/
	public $success;	
}
?>