<?php
class UserDao extends LtBaseDao
{
	public function __construct()
	{
		parent::__construct();
		$this->table = $this->db->getTDG('user');
		$this->primaryKey = 'uid';
	}
}
