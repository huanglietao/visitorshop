<?php
/**
 * TOP API: taobao.delivery.drug.deliveryconfirm request
 * 
 * @author auto create
 * @since 1.0, 2018.07.26
 */
class DeliveryDrugDeliveryconfirmRequest
{
	/** 
	 * 配送单号
	 **/
	private $deliveryOrderNo;
	
	/** 
	 * 配送员id
	 **/
	private $deviceId;
	
	/** 
	 * 纬度
	 **/
	private $latitude;
	
	/** 
	 * 定位错误
	 **/
	private $locationErrorReason;
	
	/** 
	 * 经度
	 **/
	private $longitude;
	
	private $apiParas = array();
	
	public function setDeliveryOrderNo($deliveryOrderNo)
	{
		$this->deliveryOrderNo = $deliveryOrderNo;
		$this->apiParas["delivery_order_no"] = $deliveryOrderNo;
	}

	public function getDeliveryOrderNo()
	{
		return $this->deliveryOrderNo;
	}

	public function setDeviceId($deviceId)
	{
		$this->deviceId = $deviceId;
		$this->apiParas["device_id"] = $deviceId;
	}

	public function getDeviceId()
	{
		return $this->deviceId;
	}

	public function setLatitude($latitude)
	{
		$this->latitude = $latitude;
		$this->apiParas["latitude"] = $latitude;
	}

	public function getLatitude()
	{
		return $this->latitude;
	}

	public function setLocationErrorReason($locationErrorReason)
	{
		$this->locationErrorReason = $locationErrorReason;
		$this->apiParas["location_error_reason"] = $locationErrorReason;
	}

	public function getLocationErrorReason()
	{
		return $this->locationErrorReason;
	}

	public function setLongitude($longitude)
	{
		$this->longitude = $longitude;
		$this->apiParas["longitude"] = $longitude;
	}

	public function getLongitude()
	{
		return $this->longitude;
	}

	public function getApiMethodName()
	{
		return "taobao.delivery.drug.deliveryconfirm";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		
		RequestCheckUtil::checkNotNull($this->deliveryOrderNo,"deliveryOrderNo");
		RequestCheckUtil::checkNotNull($this->latitude,"latitude");
		RequestCheckUtil::checkNotNull($this->longitude,"longitude");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
