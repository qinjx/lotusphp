<?php
class UserSigninAction extends LtAction
{
	/**
	 * 一定要有这个execute()方法
	 */
	public function execute()
	{
		$this->code = 200;
		$this->message = "Welcome, please signin";
		$this->data["username"] = "lotusphp";
	}
}