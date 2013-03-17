<?php
class UserDoLoginAction extends LtAction
{
	public function execute()
	{
		$password = md5($this->context->post('password'));
		$username = trim($this->context->post('username'));
		if ($username && $password)
		{
			$addressbookService = new AddressBookService();
			$uid = $addressbookService->getUserIdByName($username);

			if ($uid)
			{
				$data = $addressbookService->getUserById($uid);
			}
			else
			{
				$data = array();
			}

			if ($data && $data['password'] == $password)
			{
				$authCode = $data['uid'] . "\t" . $data['password'];
				$this->message = "登陆成功";
			}
			else
			{
				$this->message = "帐号密码错误";
				$authCode = '';
			}
			/**
			 * 保存时间86400秒(一天)
			 */
			C("LtCookie")->setCookie('auth', $authCode, time() + 86400);
			$this->data['forward'] = C('LtUrl')->generate('Default', 'Index');
		}
		else
		{
			$this->message = "请输入帐号和密码登陆";
			$this->data['forward'] = C('LtUrl')->generate('User', 'Login');
		}

		$this->data['baseurl'] = LtObjectUtil::singleton('LtConfig')->get('baseurl'); 
		$this->data['title'] = '用户登陆';

		$this->responseType = 'tpl';
		$this->layout = 'result';
	}
}
