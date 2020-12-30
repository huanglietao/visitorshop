<?php
/**
 * TOP API: taobao.miniapp.template.update request
 * 
 * @author auto create
 * @since 1.0, 2020.05.15
 */
class MiniappTemplateUpdateRequest
{
	/** 
	 * 要更新的投放端,目前可投放： taobao(淘宝),tmall(天猫)
	 **/
	private $clients;
	
	/** 
	 * schema信息，不填且 应用线上版本使用的templateId与传入的templateId不一致，则会报错; 一致，则复用线上版本的schema。
	 **/
	private $extJson;
	
	/** 
	 * 应用id
	 **/
	private $id;
	
	/** 
	 * 模板id
	 **/
	private $templateId;
	
	/** 
	 * 模板版本
	 **/
	private $templateVersion;
	
	private $apiParas = array();
	
	public function setClients($clients)
	{
		$this->clients = $clients;
		$this->apiParas["clients"] = $clients;
	}

	public function getClients()
	{
		return $this->clients;
	}

	public function setExtJson($extJson)
	{
		$this->extJson = $extJson;
		$this->apiParas["ext_json"] = $extJson;
	}

	public function getExtJson()
	{
		return $this->extJson;
	}

	public function setId($id)
	{
		$this->id = $id;
		$this->apiParas["id"] = $id;
	}

	public function getId()
	{
		return $this->id;
	}

	public function setTemplateId($templateId)
	{
		$this->templateId = $templateId;
		$this->apiParas["template_id"] = $templateId;
	}

	public function getTemplateId()
	{
		return $this->templateId;
	}

	public function setTemplateVersion($templateVersion)
	{
		$this->templateVersion = $templateVersion;
		$this->apiParas["template_version"] = $templateVersion;
	}

	public function getTemplateVersion()
	{
		return $this->templateVersion;
	}

	public function getApiMethodName()
	{
		return "taobao.miniapp.template.update";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		
		RequestCheckUtil::checkNotNull($this->clients,"clients");
		RequestCheckUtil::checkMaxListSize($this->clients,20,"clients");
		RequestCheckUtil::checkNotNull($this->id,"id");
		RequestCheckUtil::checkNotNull($this->templateId,"templateId");
		RequestCheckUtil::checkNotNull($this->templateVersion,"templateVersion");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
