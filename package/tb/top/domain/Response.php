<?php

/**
 * null
 * @author auto create
 */
class Response
{
	
	/** 
	 * 响应码,,string (50),
	 **/
	public $code;
	
	/** 
	 * 响应结果,success|failure,string (10),必填
	 **/
	public $flag;
	
	/** 
	 * 响应信息,,string (100),
	 **/
	public $message;	
}
?>