<?php
class UserDoRegisterAction extends LtAction
{
	public function __construct()
	{
		parent::__construct();

/**
@todo 如何验证表单内数组变量？
*/
		$this->dtds['mobile'] = new LtValidatorDtd("手机号",
			array(
				"mask" => "/^[0-9]+$/i",
				"required" => true,
				),
			array(
				"mask" => "%s只能由数字或字组成",
				"required" => "手机号必填项",
				)
			);
		$this->dtds['password'] = new LtValidatorDtd("密码",
			array(
				"min_length" => 6,
				"max_length" => 16,
				"mask" => "/^[a-z0-9]+$/i",
				"ban" => "/fuck/",
				),
			array(
				"min_length" => "%s最少%s个字符",
				"max_length" => "%s最多%s个字符",
				"mask" => "%s只能由数字或字组成",
				"ban" => "%s不能包含脏话"
				)
			);
		$this->dtds['repassword'] = new LtValidatorDtd("再次输入密码",
			array(
				"min_length" => 6,
				"max_length" => 16,
				"mask" => "/^[a-z0-9]+$/i",
				"ban" => "/fuck/",
			"equal_to"=>$_POST['password'],
				),
			array(
				"min_length" => "%s最少%s个字符",
				"max_length" => "%s最多%s个字符",
				"mask" => "%s只能由数字或字组成",
				"ban" => "%s不能包含脏话",
			"equal_to"=>"两次输入的密码不相等",
				)
			);

			$this->data['baseurl'] = C('LtConfig')->get('baseurl');
			$this->data['forward'] = 'goback';
			$this->data['title'] = 'addressbook';

			$this->responseType = 'tpl';
			$this->layout = 'result';
	}

	public function execute()
	{
		$data['username'] = $this->context->post('username');
		$data['mobile'] = $this->context->post('mobile');
		$data['email'] = $this->context->post('email');
		$data['password'] = md5($this->context->post('password'));
		$addressbookService = new AddressBookService();
		$addressbookService->addUser($data);
		$this->message = "注册成功";
		$this->data['forward'] = LtObjectUtil::singleton('LtUrl')->generate('Default', 'Index');
		$this->data['title'] = 'addressbook';
		$this->data['baseurl'] = LtObjectUtil::singleton('LtConfig')->get('baseurl');
	}
}
