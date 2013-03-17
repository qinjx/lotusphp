<?php
class GroupsDao extends LtBaseDao
{
	public function __construct()
	{
		parent::__construct();
		$this->table = $this->db->getTDG('groups');
		$this->primaryKey = 'gid';
	}
}
