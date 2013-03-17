<?php
class GroupsIndexAction extends MyAction
{
	public function execute()
	{
		$addressbook = new AddressBookService();
		$this->data['groups'] = $addressbook->getAllGroups($this->uid);

		$this->data['title'] = 'addressbook';
		$this->data['baseurl'] = LtObjectUtil::singleton('LtConfig')->get('baseurl'); 
	}
}
