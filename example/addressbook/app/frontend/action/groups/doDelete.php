<?php
class GroupsDoDeleteAction extends MyAction
{
	public function execute()
	{
		$gid = $this->context->get('gid');
		
		$addressbook = new AddressBookService();
		$addressbook->deleteGroup($this->uid, $gid);

		$this->code = 200;
		$this->message = '删除成功';
		$this->data['title'] = 'addressbook';
		$this->data['forward'] = C('LtUrl')->generate('Groups', 'Index');
		$this->data['baseurl'] = LtObjectUtil::singleton('LtConfig')->get('baseurl'); 
		
		$this->responseType = 'tpl';
		$this->layout = 'result';
	}
}
