<?php
class DefaultIndexAction extends LtAction
{
	public function __construct()
	{
		parent::__construct();
		$this->responseType = 'tpl'; // 使用模板引擎
	}
	/**
	 * 一定要有这个execute()方法
	 */
	public function execute()
	{
		$this->code = 200;
		$this->message = "Welcome LotusPHP";
		$this->data["username"] = 'username';
	}
}
