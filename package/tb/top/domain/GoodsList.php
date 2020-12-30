<?php

/**
 * 订单商品
 * @author auto create
 */
class GoodsList
{
	
	/** 
	 * 商品数量
	 **/
	public $count;
	
	/** 
	 * 商品外部编码
	 **/
	public $goods_code;
	
	/** 
	 * 商品ID
	 **/
	public $id;
	
	/** 
	 * 是否是优惠商品
	 **/
	public $is_promotion;
	
	/** 
	 * 商品名称
	 **/
	public $name;
	
	/** 
	 * 是否是优惠商品
	 **/
	public $promotion;
	
	/** 
	 * 优惠名称
	 **/
	public $promotion_name;
	
	/** 
	 * 0 未优惠 1 已优惠
	 **/
	public $promotion_status;
	
	/** 
	 * 优惠类型
	 **/
	public $promotion_type;
	
	/** 
	 * 实际价格
	 **/
	public $real_price;	
}
?>