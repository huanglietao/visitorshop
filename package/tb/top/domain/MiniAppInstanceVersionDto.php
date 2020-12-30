<?php

/**
 * 数据可能延迟，查不到隔会儿再查。
 * @author auto create
 */
class MiniAppInstanceVersionDto
{
	
	/** 
	 * 小程序app_id
	 **/
	public $app_id;
	
	/** 
	 * 版本链接。上线状态为线上地址，预览状态为预览地址，下线状态为空。
	 **/
	public $app_url;
	
	/** 
	 * 小程序版本号
	 **/
	public $app_version;
	
	/** 
	 * 发布端
	 **/
	public $client;
	
	/** 
	 * 扩展信息
	 **/
	public $ext_json;
	
	/** 
	 * 版本状态
	 **/
	public $status;
	
	/** 
	 * 模板id
	 **/
	public $template_id;
	
	/** 
	 * 模板版本
	 **/
	public $template_version;	
}
?>