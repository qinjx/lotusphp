<?php
class UserCheckAction extends LtAction
{
	public function execute()
	{
		$mobile = $this->context->get('mobile');

		$addressbookService = new AddressBookService();
		if($addressbookService->isMobileExists($mobile))
		{
			$this->message = "手机号已经注册";
		}
		else
		{
			$this->message = "可以使用";
		}
		
		$this->responseType = 'json'; // 返回json类型
	}
}
