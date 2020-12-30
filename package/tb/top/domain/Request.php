<?php

/**
 * null
 * @author auto create
 */
class Request
{
	
	/** 
	 * 缺货回告创建时间,,string (19),必填,
	 **/
	public $create_date;
	
	/** 
	 * 创建人,,string (50),,
	 **/
	public $create_emp;
	
	/** 
	 * 运单号,, string (50) , 必填,
	 **/
	public $express_code;
	
	/** 
	 * items
	 **/
	public $items;
	
	/** 
	 * 物流公司编码,,string (50), SF=顺丰、EMS=标准快递、EYB=经济快件、ZJS=宅急送、YTO=圆通  、ZTO=中通 (ZTO) 、HTKY=百世汇通、BSKY=百世快运、UC=优速、STO=申通、TTKDEX=天天快递  、QFKD=全峰、FAST=快捷、POSTB=邮政小包  、GTO=国通、YUNDA=韵达、JD=京东配送、DD=当当宅配、AMAZON=亚马逊物流、DBWL=德邦物流、DBKD=德邦快递、DBKY=德邦快运、RRS=日日顺、OTHER=其他,必填,
	 **/
	public $logistics_code;
	
	/** 
	 * 物流公司名称,,string (200),,
	 **/
	public $logistics_name;
	
	/** 
	 * ERP的发货单编码,,string (50),必填,
	 **/
	public $order_code;
	
	/** 
	 * 仓库系统的发货单编码,,string (50),,
	 **/
	public $order_id;
	
	/** 
	 * receiverInfo
	 **/
	public $receiver_info;
	
	/** 
	 * 备注,,string (200),,
	 **/
	public $remark;
	
	/** 
	 * 仓库编码,,string (50),必填,
	 **/
	public $warehouse_code;	
}
?>