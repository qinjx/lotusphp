<?php
class AddressBookService
{
	/**
	 * @var UserDao
	 */
	protected $userDao;
	/**
	 * @var GroupsDao
	 */
	protected $groupsDao;
	/**
	 * @var AddressbookDao
	 */
	protected $addressbookDao;

	public function __construct()
	{
		$this->userDao = new UserDao();
		$this->groupsDao = new GroupsDao();
		$this->addressbookDao = new AddressbookDao();
	}

	public function getAllGroups($uid)
	{
		if(empty($uid))
		{
			$result['count'] = 0;
			$result['rows'] = array();
		}
		else
		{
			$condition['where']['expression'] = "uid = :uid";
			$condition['where']['value']['uid'] = $uid;
			$condition['orderby'] = 'gid DESC';
			$result['count'] = $this->groupsDao->countByCondition($condition);
			$result['rows'] = $this->groupsDao->selectByCondition($condition);
		}
		return $result;
	}
	
	public function getGroupsById($uid, $gid)
	{
		$condition['where']['expression'] = "gid=:gid AND uid=:uid";
		$condition['where']['value']['gid'] = $gid;
		$condition['where']['value']['uid'] = $uid;

		$tmp = $this->groupsDao->selectByCondition($condition);
		return $tmp ? $tmp[0] : $tmp;
	}
	
	public function updateGroup($uid, $data)
	{
		$gid = $data['gid'];
		unset($data['gid']);
		$condition['expression'] = "gid=:gid AND uid=:uid";
		$condition['value']['gid'] = $gid;
		$condition['value']['uid'] = $uid;
		return $this->groupsDao->updateByCondition($data, $condition);
	}
	
	public function deleteGroup($uid, $gid)
	{
		if (is_array($gid))
		{
			array_map(array(&$this, 'deleteGroup'), $gid);
		}
		else
		{
			$condition['expression'] = "gid=:gid AND uid=:uid";
			$condition['value']['gid'] = $gid;
			$condition['value']['uid'] = $uid;
			$this->groupsDao->deleteByCondition($condition);
		}
	}
	
	public function addGroup($data)
	{
		return $this->groupsDao->insert($data);
	}
	/**
	 * 取当前用户通讯录的列表
	 * 
	 * @param integer $uid
	 * @param array $param
	 * @return array('count','rows')
	 */
	public function getAddressBookListByUserId($uid, $param=array())
	{
		if (empty($uid))
		{
			return array('count'=>0, 'rows'=>array());
		}
		$where = '';
		if (isset($param['op']) && 'search' == $param['op'])
		{
			if (isset($param['gid']) && -1 != $param['gid'])
			{
				$where .= " AND a.gid=" . $param['gid'];
			}
			if (isset($param['q']))
			{
				switch ($param['field'])
				{
					case 'name':
						$where .= " AND a.firstname like '%" . $param['q'] . "%'";
						break;
					case 'mobile':
						$where .= " AND a.mobile='" . $param['q'] . "'";
						break;
				}
			}
		}
		// 演示直接执行sql
		$tmp = $this->addressbookDao->query("select count(*) total
				from addressbook a
				left join groups g
				on g.gid=a.gid
				where a.uid=$uid $where");
		$result['count'] = $tmp[0]['total'];
			
		$result['rows'] = $this->addressbookDao->query("select a.id id,
				a.uid uid,
				a.gid gid,
				a.firstname firstname,
				a.lastname lastname,
				a.company company,
				a.address address,
				a.mobile mobile,
				a.phone phone,
				a.created created,
				a.modified modified,
				g.groupname groupname from addressbook a
				left join groups g
				on a.gid=g.gid
				where a.uid=$uid $where
				limit {$param['limit']} offset {$param['offset']}");
		return $result;
	}
	
	public function getAddressbook($id)
	{
		return $this->addressbookDao->selectByPrimaryKey($id);
	}
	
	public function addAddressBook($data)
	{
		return $this->addressbookDao->insert($data);
	}
	
	public function delAddressBook($id)
	{
		if (is_array($id))
		{
			array_map(array(&$this, 'delAddressBook'), $id);
		}
		else
		{
			$condition['expression'] = "id = :id";
			$condition['value']['id'] = $id;
			$this->addressbookDao->deleteByCondition($condition);
		}
	}
	
	public function updateAddressbook($data)
	{
		$this->addressbookDao->updateByPrimaryKey($data);
	}
	
	public function getUserById($uid)
	{
		return $this->userDao->selectByPrimaryKey($uid);
	}
	
	public function getUserIdByName($username)
	{
		$condition['where']['expression'] = "username = :username";
		$condition['where']['value']['username'] = $username;
		$condition['limit'] = 1;
		$tmp = array();
		$tmp = $this->userDao->selectByCondition($condition);
		return $tmp ? $tmp[0]['uid'] : $tmp;

	}
	
	public function addUser($data)
	{
		return $this->userDao->insert($data);
	}
	
	public function isMobileExists($mobile)
	{
		$condition['where']['expression'] = "mobile = :mobile";
		$condition['where']['value']['mobile'] = $mobile;
		$tmp = $this->userDao->selectByCondition($condition);
		return $tmp ? true : false;
	}
}
