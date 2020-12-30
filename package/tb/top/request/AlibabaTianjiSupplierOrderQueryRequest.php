<?php
/**
 * TOP API: alibaba.tianji.supplier.order.query request
 * 
 * @author auto create
 * @since 1.0, 2019.03.19
 */
class AlibabaTianjiSupplierOrderQueryRequest
{
	/** 
	 * 订单查询入参
	 **/
	private $paramSupplierTopQueryModel;
	
	private $apiParas = array();
	
	public function setParamSupplierTopQueryModel($paramSupplierTopQueryModel)
	{
		$this->paramSupplierTopQueryModel = $paramSupplierTopQueryModel;
		$this->apiParas["param_supplier_top_query_model"] = $paramSupplierTopQueryModel;
	}

	public function getParamSupplierTopQueryModel()
	{
		return $this->paramSupplierTopQueryModel;
	}

	public function getApiMethodName()
	{
		return "alibaba.tianji.supplier.order.query";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
