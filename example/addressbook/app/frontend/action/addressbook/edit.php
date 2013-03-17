<?php
class AddressbookEditAction extends MyAction
{
	public function execute()
	{
		$addressbook = new AddressBookService();
		$this->data['groups'] = $addressbook->getAllGroups($this->uid);

		$id = $this->context->get('id');
		$this->data['data'] = $addressbook->getAddressbook($id);

		$this->data['title'] = 'addressbook';
		$this->data['baseurl'] = LtObjectUtil::singleton('LtConfig')->get('baseurl'); 
	}
}
