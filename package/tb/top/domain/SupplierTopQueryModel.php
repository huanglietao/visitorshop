<?php

/**
 * 订单查询入参
 * @author auto create
 */
class SupplierTopQueryModel
{
	
	/** 
	 * 业务类型:7-合约及号卡分销
	 **/
	public $biz_type;
	
	/** 
	 * 分销商名称
	 **/
	public $distributor_name;
	
	/** 
	 * 订单结束时间
	 **/
	public $end_time;
	
	/** 
	 * 淘宝交易订单号
	 **/
	public $order_no;
	
	/** 
	 * 订单状态列表:1-未订购,2-订购中,3-订购中,4-订购失败,5-订购成功,6-订购取消
	 **/
	public $order_status_list;
	
	/** 
	 * 当前页
	 **/
	public $page_num;
	
	/** 
	 * 分页数量
	 **/
	public $page_size;
	
	/** 
	 * 用户账号
	 **/
	public $phone_no;
	
	/** 
	 * 订单开始时间
	 **/
	public $start_time;	
}
?>