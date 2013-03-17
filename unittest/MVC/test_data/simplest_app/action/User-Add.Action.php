<?php
class UserAddAction extends LtAction
{
	public function __construct()
	{
		parent::__construct();
		$this->responseType = 'tpl'; // 使用模板引擎
		$this->layout = 'top_navigator';
	}
	/**
	 * 一定要有这个execute()方法
	 */
	public function execute()
	{
		$this->code = 200;
		$this->message = "Welcome, please signin";
		$this->data["username"] = "lotusphp";
		$this->data['title'] = 'lotusphp';
	}
}