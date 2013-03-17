<?php
class testPassDataAction extends LtAction
{
	public function __construct()
	{
		parent::__construct();
	}
	public function execute()
	{
		$this->code = 200;
		$this->message = "Welcome";
		$this->data["username"] = "lotusphp";
		$this->data['title'] = "Welcome Lotusphp ";

		$this->data['ip'] = $_SERVER['REMOTE_ADDR'];
		$this->data['time'] = time();
		$this->data['someVar'] = 'a variable passed by $this->data[someVar]';

		$this->responseType = 'tpl'; // 使用模板引擎
		$this->layout = 'top_navigator';
	}

}