<?php

/**
 * 子订单
 * @author auto create
 */
class SubOrderDto
{
	
	/** 
	 * 购买数量
	 **/
	public $buy_amount;
	
	/** 
	 * 商品ID
	 **/
	public $item_id;
	
	/** 
	 * 商家自定义商品ID
	 **/
	public $out_itemd_id;
	
	/** 
	 * 价格
	 **/
	public $price;
	
	/** 
	 * 是否为处方药
	 **/
	public $rx;
	
	/** 
	 * 子订单ID
	 **/
	public $sub_order_id;
	
	/** 
	 * 商品名称
	 **/
	public $title;
	
	/** 
	 * 商品营销类型：0-普通，1-满就送；2-兑换券
	 **/
	public $type;
	
	/** 
	 * 商品单位
	 **/
	public $unit;	
}
?>