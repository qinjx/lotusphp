<?php
/**
 * DB connection mamager
 * @author Jianxiang Qin <TalkativeDoggy@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause New BSD License
 * @version svn:$Id: DbConnectionManager.php 964 2012-08-27 04:02:32Z zhao5908@gmail.com $
 */

/**
 * connection mamager
 * @author Jianxiang Qin <TalkativeDoggy@gmail.com>
 * @category runtime
 * @package   Lotusphp\DB
 */
class LtDbConnectionManager
{
	/** 
	 * @var array Connection management
	 * array(
	 * 	"connection"  => connection resource id,
	 * 	"expire_time" => expire time,
	 * 	"schema"      => default schema name,
	 * 	"charset"     => char set / encoding
	 * )
	 */
	static public $connectionPool;
	
	/** @var LtConfig config handle */
	public $configHandle;
	
	/** @var LtDbConnectionAdapter connection adapter */
	protected $connectionAdapter;
	
	/** @var LtDbSqlAdapter sql adapter */
	protected $sqlAdapter;
	
	/** @var array servers */
	private $servers;

	/**
	 * get connection
	 * @param string $group
	 * @param string $node
	 * @param string $role
	 * @return boolean
	 */
	public function getConnection($group, $node, $role = "master")
	{
		if(empty($this->servers))
		{
			$this->servers = $this->configHandle->get("db.servers");
		}
		if (($connection = $this->getNewConnection($group, $node, $role)) ||($connection = $this->getCachedConnection($group, $node, $role)))
		{
			return array(
				"connectionAdapter" => $this->connectionAdapter,
				"connectionResource" => $connection
			);
		}
		else
		{
			trigger_error("db server can not be connected: group=$group, node=$node, role=$role", E_USER_ERROR);
			return false;
		}
	}

	/**
	 * get connection key
	 * @param array $connConf
	 * @return string
	 */
	protected function getConnectionKey($connConf)
	{
		return $connConf['adapter'] . $connConf['host'] . $connConf['port'] . $connConf['username'] . $connConf['dbname'];
	}

	/**
	 * save connection
	 * @param array $connConf
	 * @param resource $connection
	 * @param int $ttl
	 */
	protected function saveConnection($connConf, $connection, $ttl)
	{
		$connectionInfo = array(
			"connection"  => $connection,
			"expire_time" => time() + $ttl,
			"schema"      => $connConf["schema"],
			"charset"     => $connConf["charset"],
		);
		self::$connectionPool[$this->getConnectionKey($connConf)] = $connectionInfo;
	}

	/**
	 * get cached connection
	 * @param string $group
	 * @param string $node
	 * @param string $role
	 * @return boolean
	 */
	protected function getCachedConnection($group, $node, $role)
	{
		foreach($this->servers[$group][$node][$role] as $hostConfig)
		{
			$key = $this->getConnectionKey($hostConfig);
			if(isset(self::$connectionPool[$key]) && time() < self::$connectionPool[$key]['expire_time'])
			{//cached connection resource FOUND
				$connectionInfo = self::$connectionPool[$key];
				if ($connectionInfo["schema"] != $hostConfig["schema"] || $connectionInfo["charset"] != $hostConfig["charset"])
				{//检查当前schema和charset与用户要操作的目标不一致
					$hostConfig = $this->servers[$group][$node][$role][$hostIndexArray[$hashNumber]];
					$dbFactory = new LtDbAdapterFactory;
					$this->connectionAdapter = $dbFactory->getConnectionAdapter($hostConfig["connection_adapter"]);
					$this->sqlAdapter = $dbFactory->getSqlAdapter($hostConfig["sql_adapter"]);
					if ($connectionInfo["schema"] != $hostConfig["schema"])
					{
						if (false === $this->connectionAdapter->exec($this->sqlAdapter->setSchema($hostConfig["schema"]), $connection))
						{
							trigger_error("error occured when change schema: group=$group, node=$node, role=$role, schema=" . $hostConfig["schema"], E_USER_ERROR);
						}
					}
					if ($connectionInfo["charset"] != $hostConfig["charset"])
					{
						$this->connectionAdapter->exec($this->sqlAdapter->setCharset($hostConfig["charset"]), $connectionInfo["connection"]);
					}
					$this->saveConnection($hostConfig, $connectionInfo["connection"], $hostConfig["connection_ttl"]);
				}
				return $connectionInfo["connection"];
			}
		}
		return false;
	}

	/**
	 * get new connection
	 * @param string $group
	 * @param string $node
	 * @param string $role
	 * @return boolean
	 */
	protected function getNewConnection($group, $node, $role)
	{
		$hostTotal = count($this->servers[$group][$node][$role]);
		$hostIndexArray = array_keys($this->servers[$group][$node][$role]);
		while ($hostTotal)
		{
			$hashNumber = substr(microtime(),7,1) % $hostTotal;
			$hostConfig = $this->servers[$group][$node][$role][$hostIndexArray[$hashNumber]];
			$dbFactory = new LtDbAdapterFactory;
			$this->connectionAdapter = $dbFactory->getConnectionAdapter($hostConfig["connection_adapter"]);
			$this->sqlAdapter = $dbFactory->getSqlAdapter($hostConfig["sql_adapter"]);
			if ($connection = $this->connectionAdapter->connect($hostConfig))
			{
				$sql = $this->sqlAdapter->setSchema($hostConfig["schema"]);
				if(!empty($sql))
				{
					if (false === $this->connectionAdapter->exec($sql, $connection))
					{
						trigger_error("error occured when change schema: group=$group, node=$node, role=$role, schema=" . $hostConfig["schema"], E_USER_ERROR);
					}
				}
				$sql = $this->sqlAdapter->setCharset($hostConfig["charset"]);
				if(!empty($sql))
				{
					$this->connectionAdapter->exec($sql, $connection);
				}
				$this->saveConnection($hostConfig, $connection, $hostConfig["connection_ttl"]);
				return $connection;
			}
			else
			{
				//trigger_error('connection fail', E_USER_WARNING);
				//delete the unavailable server
				for ($i = $hashNumber; $i < $hostTotal - 1; $i ++)
				{
					$hostIndexArray[$i] = $hostIndexArray[$i+1];
				}
				unset($hostIndexArray[$hostTotal-1]);
				$hostTotal --;
			}//end else
		}//end while
		return false;
	}

	/**
	 * change schema
	 */
	protected function changeSchema()
	{

	}
}