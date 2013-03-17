<?php 
class testUsingBlankLayoutAction extends LtAction
{
	public function execute()
	{
		$this->responseType = 'tpl'; // 使用模板引擎
		// $this->layout = 'top_navigator'; // 不用布局
	}	
}