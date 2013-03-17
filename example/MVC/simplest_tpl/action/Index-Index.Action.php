<?php
class IndexIndexAction extends LtAction
{
	public function execute()
	{
		$this->code = 200;
		$this->message = "Welcome, please signin";
		$this->data['title'] = "Welcome Lotusphp ";
		$this->data["username"] = "lotusphp";

		$this->responseType = 'tpl'; // 使用模板引擎
		$this->layout = 'top_navigator'; //使用布局
	}
}