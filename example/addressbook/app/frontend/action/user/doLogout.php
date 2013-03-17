<?php
class UserDoLogoutAction extends MyAction
{
	public function execute()
	{
		C('LtCookie')->delCookie('auth');
		$this->code = 200;
		$this->data['forward'] = C('Lturl')->generate('User', 'Login');

		$this->data['baseurl'] = C('LtConfig')->get('baseurl'); 
		$this->data['title'] = '退出成功';

		$this->responseType = 'tpl';
		$this->layout = 'result';
	}
}
