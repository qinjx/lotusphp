<?php
class GroupsDoAddAction extends MyAction
{
	public function execute()
	{
		$data = $this->context->post('data');
		$data['uid'] = $this->uid;
				
		$addressbook = new AddressBookService();
		$addressbook->addGroup($data);

		$this->code = 200;
		$this->data['forward'] = C('LtUrl')->generate('Groups', 'Index');

		$this->data['title'] = 'addressbook';
		$this->data['baseurl'] = LtObjectUtil::singleton('LtConfig')->get('baseurl');
		
		$this->layout = 'result';
	}
}
