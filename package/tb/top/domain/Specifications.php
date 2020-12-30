<?php

/**
 * 机油
 * @author auto create
 */
class Specifications
{
	
	/** 
	 * 品牌
	 **/
	public $brand;
	
	/** 
	 * 级别
	 **/
	public $level;
	
	/** 
	 * 型号
	 **/
	public $model;
	
	/** 
	 * 数量，单位是升时，可以保留一位小数
	 **/
	public $number;
	
	/** 
	 * 价格,单位（元）,最多保留两位小数如11.11
	 **/
	public $price;
	
	/** 
	 * 类型
	 **/
	public $type;
	
	/** 
	 * 单位，可输入项：升、套、桶、瓶
	 **/
	public $unit;	
}
?>