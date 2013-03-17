<?php
class GroupsDoEditAction extends MyAction
{
	public function execute()
	{
		$data = $this->context->post('data');

		$addressbook = new AddressBookService();
		$addressbook->updateGroup($this->uid, $data);

		$this->code = 200;
		$this->data['forward'] = C('LtUrl')->generate('Groups', 'Index');

		$this->data['title'] = 'addressbook';
		$this->data['baseurl'] = LtObjectUtil::singleton('LtConfig')->get('baseurl');
		
		$this->responseType = 'tpl';
		$this->layout = 'result'; // 使用不同的页面布局
	}
}
