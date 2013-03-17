<?php
class GroupsEditAction extends MyAction
{
	public function execute()
	{
		$gid = $this->context->get('gid');

		$addressbook = new AddressBookService();
		$this->data['group'] = $addressbook->getGroupsById($this->uid, $gid);

		$this->data['title'] = 'addressbook';
		$this->data['baseurl'] = LtObjectUtil::singleton('LtConfig')->get('baseurl'); 
	}
}
