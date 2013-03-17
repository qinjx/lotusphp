<?php
/**
 * DB
 * @author Jianxiang Qin <TalkativeDoggy@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause New BSD License
 * @version svn:$Id: Db.php 964 2012-08-27 04:02:32Z zhao5908@gmail.com $
 */

/**
 * Distributed database module
 * @author Jianxiang Qin <TalkativeDoggy@gmail.com>
 * @category runtime
 * @package   Lotusphp\DB
 */
class LtDb
{
	/** @var LtConfig config handle */
	public $configHandle;
	
	/** @var string group */
	public $group;
	
	/** @var string node */
	public $node;
	
	/** @var LtDbHandle db handle */
	protected $dbh;

	/**
	 * construct
	 */
	public function __construct()
	{
		if (! $this->configHandle instanceof LtConfig)
		{
			if (class_exists("LtObjectUtil", false))
			{
				$this->configHandle = LtObjectUtil::singleton("LtConfig");
			}
			else
			{
				$this->configHandle = new LtConfig;
			}
		}
	}

	/**
	 * init
	 */
	public function init()
	{
		$this->dbh = new LtDbHandle;
		$this->dbh->configHandle = $this->configHandle;
		$this->dbh->group = $this->getGroup();
		$this->dbh->node = $this->getNode();
		$this->dbh->init();
	}

	/**
	 * get db handle
	 * @return LtDbHandle
	 */
	public function getDbHandle()
	{
		return $this->dbh;
	}

	/**
	 * get table data gateway
	 * @param string $tableName
	 * @return LtDbTableDataGateway
	 */
	public function getTDG($tableName)
	{
		$tg = new LtDbTableDataGateway;
		$tg->configHandle = $this->configHandle;
		$tg->tableName = $tableName;
		$tg->createdColumn = 'created';
		$tg->modifiedColumn = 'modified';
		$tg->dbh = $this->dbh;
		return $tg;
	}

	/**
	 * get sql map client
	 * @return LtDbSqlMapClient
	 */
	public function getSqlMapClient()
	{
		$smc = new LtDbSqlMapClient;
		$smc->configHandle = $this->configHandle;
		$smc->dbh = $this->dbh;
		return $smc;
	}

	/**
	 * change node
	 * @param string $node
	 */
	public function changeNode($node)
	{
		$this->node = $node;
		$this->dbh->node = $node;
	}

	/**
	 * get group
	 * @return boolean
	 */
	protected function getGroup()
	{
		if ($this->group)
		{
			return $this->group;
		}
		$servers = $this->configHandle->get("db.servers");
		if (1 == count($servers))
		{
			return key($servers);
		}
		return false;
	}

	/**
	 * get node
	 * @return boolean
	 */
	protected function getNode()
	{
		if ($this->node)
		{
			return $this->node;
		}
		$servers = $this->configHandle->get("db.servers");
		if (1 == count($servers[$this->getGroup()]))
		{
			return key($servers[$this->getGroup()]);
		}
		return false;
	}
}