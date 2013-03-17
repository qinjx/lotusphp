<?php
/**
 * 每个应用都要有自己的action基类，
 * 该应用下的action都继承该action基类，
 * 目的是方便以后的扩展
 */
class MyAction extends LtAction
{
	protected $uid;
		
	/**
	 * (non-PHPdoc)
	 * @see LtAction::afterConstruct()
	 */
	public function afterConstruct()
	{
		$this->responseType = 'tpl';
		$this->layout = 'index';
	}

	/**
	 * (non-PHPdoc)
	 * @see LtAction::beforeExecute()
	 */
	public function beforeExecute()
	{
		$this->context->admin_id = 0;
		$authCode = C('LtCookie')->getCookie('auth');
		if ($authCode)
		{
			list($uid, $password) = explode("\t", $authCode);
			$addressbookService = new AddressBookService();
			$data = $addressbookService->getUserById($uid);

			if ($data && $data['password'] == $password)
			{
				$this->data['username'] = $data['username'];
				$this->data['uid'] = $data['uid'];
				$this->uid = $data['uid'];
			}
			else
			{
				$this->data['username'] = '';
				$this->data['uid'] = 0;
				C('LtCookie')->delCookie('auth');
			}
		}
		if (!$this->data['uid'])
		{
			header("Location: " . C('LtUrl')->generate('User', 'Login'));
		} 
	}
	
	/**
	 * @return POST GET etc.
	 */
	protected function getRequestMethod()
	{
		if (isset($_SERVER['REQUEST_METHOD']))
		{
			return strtoupper($_SERVER['REQUEST_METHOD']);
		}
		return '';
	}
	
	protected function isRequestMethod($string)
	{
		if (isset($_SERVER['REQUEST_METHOD']))
		{
			return strtoupper($_SERVER['REQUEST_METHOD']) == strtoupper($string);
		}
		return false;
	}
	
	/**
	 * 转到url
	 *
	 * @param string $url
	 * @param string $default
	 */
	protected function forward($url, $default='/')
	{
		if (filter_var($url, FILTER_VALIDATE_URL))
		{
			header('location:'.$url);
		}
		else
		{
			header('location:'.$default);
		}
		exit;
	}
	
	/**
	 * 重定向到module action
	 *
	 * @param string $module
	 * @param string $action
	 * @param array $args
	 */
	protected function redirect($module, $action, $args = array())
	{
		$url = new LtUrl();
		$url->init();
		header('location:'.$url->generate($module, $action, $args));
		exit;
	}
}
