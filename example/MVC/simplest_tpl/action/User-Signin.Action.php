<?php
class UserSigninAction extends LtAction
{
	public function __construct()
	{
		parent::__construct();
		$this->dtds['username'] = new LtValidatorDtd("username",
			array("max_length" => 8,
				"mask" => "/^[a-z0-9]+$/i",
				"ban" => "/fuck/",
				),
			array(
				"max_length" => "最大长度8",
				"mask" => "用户名只能由数字或字组成",
				"ban" => "用户名不能包含脏话"
				)
			);
		$this->responseType = 'tpl'; // 使用模板引擎
	}
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
