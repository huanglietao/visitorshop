<?php
/**
 * TOP API: taobao.car.service.trade.create request
 * 
 * @author auto create
 * @since 1.0, 2018.07.25
 */
class CarServiceTradeCreateRequest
{
	/** 
	 * 预约时间
	 **/
	private $bookingTime;
	
	/** 
	 * 汽车信息
	 **/
	private $carInfo;
	
	/** 
	 * 生成的token有效期，至少大于5分钟
	 **/
	private $expiryDate;
	
	/** 
	 * 商品ID
	 **/
	private $itemId;
	
	/** 
	 * isv生成的汽车保养订单ID
	 **/
	private $merchantOrderId;
	
	/** 
	 * 买家混淆user ID
	 **/
	private $mixUserId;
	
	/** 
	 * 创建的插件实例ID
	 **/
	private $pluginInstanceId;
	
	/** 
	 * 保养服务信息
	 **/
	private $serviceInfo;
	
	/** 
	 * 门店信息
	 **/
	private $storeInfo;
	
	/** 
	 * 总价格,单位（元）
	 **/
	private $totalPrice;
	
	private $apiParas = array();
	
	public function setBookingTime($bookingTime)
	{
		$this->bookingTime = $bookingTime;
		$this->apiParas["booking_time"] = $bookingTime;
	}

	public function getBookingTime()
	{
		return $this->bookingTime;
	}

	public function setCarInfo($carInfo)
	{
		$this->carInfo = $carInfo;
		$this->apiParas["car_info"] = $carInfo;
	}

	public function getCarInfo()
	{
		return $this->carInfo;
	}

	public function setExpiryDate($expiryDate)
	{
		$this->expiryDate = $expiryDate;
		$this->apiParas["expiry_date"] = $expiryDate;
	}

	public function getExpiryDate()
	{
		return $this->expiryDate;
	}

	public function setItemId($itemId)
	{
		$this->itemId = $itemId;
		$this->apiParas["item_id"] = $itemId;
	}

	public function getItemId()
	{
		return $this->itemId;
	}

	public function setMerchantOrderId($merchantOrderId)
	{
		$this->merchantOrderId = $merchantOrderId;
		$this->apiParas["merchant_order_id"] = $merchantOrderId;
	}

	public function getMerchantOrderId()
	{
		return $this->merchantOrderId;
	}

	public function setMixUserId($mixUserId)
	{
		$this->mixUserId = $mixUserId;
		$this->apiParas["mix_user_id"] = $mixUserId;
	}

	public function getMixUserId()
	{
		return $this->mixUserId;
	}

	public function setPluginInstanceId($pluginInstanceId)
	{
		$this->pluginInstanceId = $pluginInstanceId;
		$this->apiParas["plugin_instance_id"] = $pluginInstanceId;
	}

	public function getPluginInstanceId()
	{
		return $this->pluginInstanceId;
	}

	public function setServiceInfo($serviceInfo)
	{
		$this->serviceInfo = $serviceInfo;
		$this->apiParas["service_info"] = $serviceInfo;
	}

	public function getServiceInfo()
	{
		return $this->serviceInfo;
	}

	public function setStoreInfo($storeInfo)
	{
		$this->storeInfo = $storeInfo;
		$this->apiParas["store_info"] = $storeInfo;
	}

	public function getStoreInfo()
	{
		return $this->storeInfo;
	}

	public function setTotalPrice($totalPrice)
	{
		$this->totalPrice = $totalPrice;
		$this->apiParas["total_price"] = $totalPrice;
	}

	public function getTotalPrice()
	{
		return $this->totalPrice;
	}

	public function getApiMethodName()
	{
		return "taobao.car.service.trade.create";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		
		RequestCheckUtil::checkNotNull($this->bookingTime,"bookingTime");
		RequestCheckUtil::checkNotNull($this->itemId,"itemId");
		RequestCheckUtil::checkNotNull($this->merchantOrderId,"merchantOrderId");
		RequestCheckUtil::checkMaxLength($this->merchantOrderId,60,"merchantOrderId");
		RequestCheckUtil::checkNotNull($this->mixUserId,"mixUserId");
		RequestCheckUtil::checkNotNull($this->pluginInstanceId,"pluginInstanceId");
		RequestCheckUtil::checkNotNull($this->totalPrice,"totalPrice");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
