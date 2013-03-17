<?php 
class testUsingComponentAction extends LtAction
{
	public function execute()
	{
		$this->code = 200;
		$this->message = "Welcome, please signin";
		$this->data["username"] = "lotusphp";
		$this->data['title'] = "Welcome Lotusphp ";

		$this->responseType = 'tpl'; // 使用模板引擎
		$this->layout = 'top_navigator';
	}
}