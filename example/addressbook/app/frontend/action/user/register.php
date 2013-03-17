<?php
class UserRegisterAction extends LtAction
{
	public function __construct()
	{
		parent::__construct();
		$this->responseType = 'tpl';
		$this->layout = 'index';
	}
	public function execute()
	{
		$this->data['title'] = "用户注册";
		$this->data['username'] = "lotusphp";
		$this->data['baseurl'] = LtObjectUtil::singleton('LtConfig')->get('baseurl'); 
	}
}
