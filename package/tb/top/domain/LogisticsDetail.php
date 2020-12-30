<?php

/**
 * 返回结果
 * @author auto create
 */
class LogisticsDetail
{
	
	/** 
	 * 配送单取消时间
	 **/
	public $cancel_time;
	
	/** 
	 * 配送完成时间
	 **/
	public $complete_time;
	
	/** 
	 * 配送单确认时间
	 **/
	public $confirm_time;
	
	/** 
	 * 骑手电话
	 **/
	public $dispatcher_mobile;
	
	/** 
	 * 骑手姓名
	 **/
	public $dispatcher_name;
	
	/** 
	 * 骑手取货时间
	 **/
	public $fetch_time;
	
	/** 
	 * 物流商
	 **/
	public $logistics_name;
	
	/** 
	 * 物流状态
	 **/
	public $logistics_status;
	
	/** 
	 * 订单id
	 **/
	public $order_id;
	
	/** 
	 * 配送单下单时间
	 **/
	public $send_time;	
}
?>