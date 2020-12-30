<?php
/**
 * TOP API: taobao.miniapp.template.updateapp request
 * 
 * @author auto create
 * @since 1.0, 2020.05.14
 */
class MiniappTemplateUpdateappRequest
{
	/** 
	 * 应用id，如果应用在对应端上已有的最新版本所使用的模板id,模板version和extjson，与本次入参一致，则认为不需要更新，返回已有的版本。
	 **/
	private $appId;
	
	/** 
	 * 要更新的投放端,目前可投放： taobao(淘宝),tmall(天猫)
	 **/
	private $clients;
	
	/** 
	 * 扩展信息。线上版本使用的template_id与传入的template_id一致时，可不填。
	 **/
	private $extJson;
	
	/** 
	 * 模板id
	 **/
	private $templateId;
	
	/** 
	 * 模板版本
	 **/
	private $templateVersion;
	
	private $apiParas = array();
	
	public function setAppId($appId)
	{
		$this->appId = $appId;
		$this->apiParas["app_id"] = $appId;
	}

	public function getAppId()
	{
		return $this->appId;
	}

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
		return "taobao.miniapp.template.updateapp";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		
		RequestCheckUtil::checkNotNull($this->appId,"appId");
		RequestCheckUtil::checkNotNull($this->clients,"clients");
		RequestCheckUtil::checkMaxListSize($this->clients,20,"clients");
		RequestCheckUtil::checkNotNull($this->templateId,"templateId");
		RequestCheckUtil::checkNotNull($this->templateVersion,"templateVersion");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
