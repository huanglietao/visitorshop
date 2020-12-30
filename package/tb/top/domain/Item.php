<?php

/**
 * 项目
 * @author auto create
 */
class Item
{
	
	/** 
	 * 实际入库数量,Item1234,string(50),,
	 **/
	public $in_count;
	
	/** 
	 * 库存类型(1:可销售库存.101:残次),HZ1234,string(500),,
	 **/
	public $inventory_type;
	
	/** 
	 * 实际出库数量,Item1234,string(50),,
	 **/
	public $out_count;
	
	/** 
	 * 计划调拨数量
	 **/
	public $plan_count;
	
	/** 
	 * 货品编码,HZ1234,string(50),,
	 **/
	public $sc_item_code;	
}
?>