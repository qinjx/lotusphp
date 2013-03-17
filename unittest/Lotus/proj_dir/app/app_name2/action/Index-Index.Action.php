<?php
class IndexIndexAction extends LtAction
{
	/**
	 * 一定要有这个execute()方法
	 */
	public function execute()
	{
		$this->responseType = 'html';
		$this->code = 200;
		$this->message = "Welcome LotusPHP";
	}
}
