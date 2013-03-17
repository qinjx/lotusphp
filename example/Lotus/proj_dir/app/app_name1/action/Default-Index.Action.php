<?php
class DefaultIndexAction extends LtAction
{
	/**
	 * 一定要有这个execute()方法
	 */
	public function execute()
	{

		$this->code = 200;
		$this->message = "Welcome to Lotusphp PHP framework!";
		$this->data["name"] = 'lotusphp';
	}
}
