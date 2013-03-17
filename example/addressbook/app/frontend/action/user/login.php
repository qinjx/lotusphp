<?php
class UserLoginAction extends LtAction
{
	public function __construct()
	{
		parent::__construct();
		$this->responseType = 'tpl';
	}
	public function execute()
	{
		$this->code = 200;
		$this->message = "Welcome, please signin";
		$this->data["username"] = "lotusphp";
		$this->data['baseurl'] = LtObjectUtil::singleton('LtConfig')->get('baseurl'); 
	}
}
