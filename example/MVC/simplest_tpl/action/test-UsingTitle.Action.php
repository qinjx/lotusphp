<?php
class testUsingTitleAction extends LtAction
{
	public function execute()
	{
		$this->data['title'] = "How to use title";

		$this->responseType = 'tpl'; // 使用模板引擎
		$this->layout = 'top_navigator';
	}
}