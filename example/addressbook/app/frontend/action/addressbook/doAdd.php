<?php
class AddressbookDoAddAction extends MyAction
{
	public function execute()
	{
		$data = $this->context->post('data');
		$data['uid'] = $this->uid;
		
		$addressbook = new AddressBookService();
		$addressbook->addAddressBook($data);

		$this->code = 200;
		$this->data['forward'] = C('LtUrl')->generate('Default', 'Index');

		$this->data['title'] = 'addressbook';
		$this->data['baseurl'] = LtObjectUtil::singleton('LtConfig')->get('baseurl');
		
		$this->layout = 'result';
	}
}
