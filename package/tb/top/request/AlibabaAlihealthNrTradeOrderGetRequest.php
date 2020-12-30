<?php
/**
 * TOP API: alibaba.alihealth.nr.trade.order.get request
 * 
 * @author auto create
 * @since 1.0, 2018.12.13
 */
class AlibabaAlihealthNrTradeOrderGetRequest
{
	/** 
	 * 淘宝订单ID
	 **/
	private $orderId;
	
	private $apiParas = array();
	
	public function setOrderId($orderId)
	{
		$this->orderId = $orderId;
		$this->apiParas["order_id"] = $orderId;
	}

	public function getOrderId()
	{
		return $this->orderId;
	}

	public function getApiMethodName()
	{
		return "alibaba.alihealth.nr.trade.order.get";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		
		RequestCheckUtil::checkNotNull($this->orderId,"orderId");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
