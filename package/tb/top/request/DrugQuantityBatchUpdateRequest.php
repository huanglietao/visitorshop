<?php
/**
 * TOP API: taobao.drug.quantity.batch.update request
 * 
 * @author auto create
 * @since 1.0, 2019.03.22
 */
class DrugQuantityBatchUpdateRequest
{
	/** 
	 * 商品ID和库存
	 **/
	private $outItemIdQuantityMap;
	
	/** 
	 * 外部店铺ID
	 **/
	private $outStoreId;
	
	private $apiParas = array();
	
	public function setOutItemIdQuantityMap($outItemIdQuantityMap)
	{
		$this->outItemIdQuantityMap = $outItemIdQuantityMap;
		$this->apiParas["out_item_id_quantity_map"] = $outItemIdQuantityMap;
	}

	public function getOutItemIdQuantityMap()
	{
		return $this->outItemIdQuantityMap;
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
		return "taobao.drug.quantity.batch.update";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		
		RequestCheckUtil::checkNotNull($this->outItemIdQuantityMap,"outItemIdQuantityMap");
		RequestCheckUtil::checkNotNull($this->outStoreId,"outStoreId");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
