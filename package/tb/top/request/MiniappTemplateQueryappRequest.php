<?php
/**
 * TOP API: taobao.miniapp.template.queryapp request
 * 
 * @author auto create
 * @since 1.0, 2020.05.15
 */
class MiniappTemplateQueryappRequest
{
	/** 
	 * 分页号,>=1
	 **/
	private $pageNum;
	
	/** 
	 * 分页大小，最大50，按照小程序Id倒序
	 **/
	private $pageSize;
	
	/** 
	 * 模板id
	 **/
	private $templateId;
	
	private $apiParas = array();
	
	public function setPageNum($pageNum)
	{
		$this->pageNum = $pageNum;
		$this->apiParas["page_num"] = $pageNum;
	}

	public function getPageNum()
	{
		return $this->pageNum;
	}

	public function setPageSize($pageSize)
	{
		$this->pageSize = $pageSize;
		$this->apiParas["page_size"] = $pageSize;
	}

	public function getPageSize()
	{
		return $this->pageSize;
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

	public function getApiMethodName()
	{
		return "taobao.miniapp.template.queryapp";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		
		RequestCheckUtil::checkNotNull($this->pageNum,"pageNum");
		RequestCheckUtil::checkNotNull($this->pageSize,"pageSize");
		RequestCheckUtil::checkNotNull($this->templateId,"templateId");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
