<?php
class AddressbookAddAction extends MyAction
{
	public function execute()
	{
		$uid = $this->uid;
		$addressbook = new AddressBookService();
		$this->data['groups'] = $addressbook->getAllGroups($uid);
		
		$this->data['title'] = 'addressbook';
		$this->data['baseurl'] = LtObjectUtil::singleton('LtConfig')->get('baseurl'); 
	}
}
