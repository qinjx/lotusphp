<?php
class AddressbookDao extends LtBaseDao
{
	public function __construct()
	{
		parent::__construct();
		$this->table = $this->db->getTDG('addressbook');
		$this->primaryKey = 'id';
	}
}
