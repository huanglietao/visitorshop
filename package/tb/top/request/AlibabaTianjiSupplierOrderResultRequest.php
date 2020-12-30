<?php
/**
 * TOP API: alibaba.tianji.supplier.order.result request
 * 
 * @author auto create
 * @since 1.0, 2019.03.19
 */
class AlibabaTianjiSupplierOrderResultRequest
{
	/** 
	 * 供应商处理订单结果反馈参数
	 **/
	private $supplierOrderResultModel;
	
	private $apiParas = array();
	
	public function setSupplierOrderResultModel($supplierOrderResultModel)
	{
		$this->supplierOrderResultModel = $supplierOrderResultModel;
		$this->apiParas["supplier_order_result_model"] = $supplierOrderResultModel;
	}

	public function getSupplierOrderResultModel()
	{
		return $this->supplierOrderResultModel;
	}

	public function getApiMethodName()
	{
		return "alibaba.tianji.supplier.order.result";
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
