<?php
/**
 * TOP API: taobao.drug.price.batch.update request
 * 
 * @author auto create
 * @since 1.0, 2019.03.22
 */
class DrugPriceBatchUpdateRequest
{
	/** 
	 * 商品ID和价格
	 **/
	private $outItemIdPriceMap;
	
	/** 
	 * 外部店铺ID
	 **/
	private $outStoreId;
	
	private $apiParas = array();
	
	public function setOutItemIdPriceMap($outItemIdPriceMap)
	{
		$this->outItemIdPriceMap = $outItemIdPriceMap;
		$this->apiParas["out_item_id_price_map"] = $outItemIdPriceMap;
	}

	public function getOutItemIdPriceMap()
	{
		return $this->outItemIdPriceMap;
	}

	public function setOutStoreId($outStoreId)
	{
		$this->outStoreId = $outStoreId;
		$this->apiParas["out_store_id"] = $outStoreId;
	}

	public function getOutStoreId()
	{
		return $this->outStoreId;
	}

	public function getApiMethodName()
	{
		return "taobao.drug.price.batch.update";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		
		RequestCheckUtil::checkNotNull($this->outItemIdPriceMap,"outItemIdPriceMap");
		RequestCheckUtil::checkNotNull($this->outStoreId,"outStoreId");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
