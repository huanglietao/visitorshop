<?php
/**
 * TOP API: taobao.miniapp.template.onlineapp request
 * 
 * @author auto create
 * @since 1.0, 2020.04.30
 */
class MiniappTemplateOnlineappRequest
{
	/** 
	 * 小程序app_id
	 **/
	private $appId;
	
	/** 
	 * 待上线的预览版本号
	 **/
	private $appVersion;
	
	/** 
	 * 要更新的投放端,目前可投放： taobao(淘宝),tmall(天猫)
	 **/
	private $clients;
	
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

	public function setAppVersion($appVersion)
	{
		$this->appVersion = $appVersion;
		$this->apiParas["app_version"] = $appVersion;
	}

	public function getAppVersion()
	{
		return $this->appVersion;
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
		return "taobao.miniapp.template.onlineapp";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		
		RequestCheckUtil::checkNotNull($this->appId,"appId");
		RequestCheckUtil::checkNotNull($this->appVersion,"appVersion");
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
