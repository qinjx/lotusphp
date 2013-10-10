<?php
/**
 * DB handle
 * @author Jianxiang Qin <TalkativeDoggy@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause New BSD License
 * @version svn:$Id: DbHandle.php 964 2012-08-27 04:02:32Z zhao5908@gmail.com $
 */

/**
 * db handle
 * @author Jianxiang Qin <TalkativeDoggy@gmail.com>
 * @category runtime
 * @package   Lotusphp\DB
 */
class LtDbHandle
{
	/** @var LtConfig config handle */
	public $configHandle;
	
	/** @var string group */
	public $group;
	
	/** @var string node */
	public $node;
	
	/** @var string role master or slave */
	public $role = "master";
	
	/** @var LtDbConnectionAdapter db connection adapter */
	public $connectionAdapter;
	
	/** @var resource connection resource */
	public $connectionResource;
	
	/** @var LtDbSqlAdapter sql adapter */
	public $sqlAdapter;
	
	/** @var LtDbConnectionManager db connection manager */
	protected $connectionManager;
	
	/** @var array servers */
	private $servers;

	/**
	 * construct
	 */
	public function __construct()
	{
	}

	/**
	 * init
	 */
	public function init()
	{
		if(empty($this->servers))
		{
			$this->servers = $this->configHandle->get("db.servers");
		}
		$this->connectionManager = new LtDbConnectionManager;
		$this->connectionManager->configHandle = $this->configHandle;
		$this->sqlAdapter = $this->getCurrentSqlAdapter();
		$connectionInfo = $this->connectionManager->getConnection($this->group, $this->node, $this->role);
		$this->connectionAdapter = $connectionInfo["connectionAdapter"];
		$this->connectionResource = $connectionInfo["connectionResource"];
	}

	/**
	 * Trancaction methods
	 */
	public function beginTransaction()
	{
		return $this->connectionAdapter->exec($this->sqlAdapter->beginTransaction(), $this->connectionResource);
	}

	/**
	 * commit
	 * @return boolean|int
	 */
	public function commit()
	{
		return $this->connectionAdapter->exec($this->sqlAdapter->commit(), $this->connectionResource);
	}

	/**
	 * rollback
	 * @return boolean|int
	 */
	public function rollBack()
	{
		return $this->connectionAdapter->exec($this->sqlAdapter->rollBack(), $this->connectionResource);
	}

	/**
	 * Execute an sql query 每次只能执行一条SQL
	 * 
	 * @param  $sql 
	 * @param  $bind 
	 * @param  $forceUseMaster 
	 * @return bool|array|int false on query failed
	 *           --sql type--                         --return value--
	 *           SELECT, SHOW, DESECRIBE, EXPLAIN     rowset or NULL when no record found
	 *           INSERT                               the ID generated for an AUTO_INCREMENT column
	 *           UPDATE, DELETE, REPLACE              affected count
	 *           USE, DROP, ALTER, CREATE, SET etc    true
	 * @notice 每次只能执行一条SQL
	 *           不要通过此接口执行USE DATABASE, SET NAMES这样的语句
	 */
	public function query($sql, $bind = null, $forceUseMaster = false)
	{
		$sql = trim($sql);
		if (empty($sql))
		{
			trigger_error('the SQL statement is empty');
		}
		$queryType = $this->sqlAdapter->detectQueryType($sql);
		switch ($queryType)
		{
			case "SELECT":
				if (!$forceUseMaster && isset($this->servers[$this->group][$this->node]["slave"]))
				{
					$this->role = "slave";
				}
				$queryMethod = "select";
				break;
			case "INSERT":
				$this->role = "master";
				$queryMethod = "insert";
				break;
			case "CHANGE_ROWS":
				$this->role = "master";
				$queryMethod = "changeRows";
				break;
			case "SET_SESSION_VAR":
				$queryMethod = "setSessionVar";
				break;
			case "OTHER":
			default:
				$this->role = "master";
				$queryMethod = "other";
				break;
		}
		$connectionInfo = $this->connectionManager->getConnection($this->group, $this->node, $this->role);
		$this->connectionAdapter = $connectionInfo["connectionAdapter"];
		$this->connectionResource = $connectionInfo["connectionResource"];
		if (is_array($bind) && 0 < count($bind))
		{
			$sql = $this->bindParameter($sql, $bind);
		}
		return $this->$queryMethod($sql, $this->connectionResource);
	}
	/**
	 * function posted by renlu
	 * @param string $str
	 * @return string
	 */
	public function escape($str)
	{
		return $this->connectionAdapter->escape($str, $this->connectionResource);
	}
	/**
	 * function posted by renlu
	 */
	public function insertid()
	{
		return $this->connectionAdapter->lastInsertId($this->connectionResource);
	}
	/**
	 * Generate complete sql from sql template (with placeholder) and parameter
	 * 
	 * @param string $sql 
	 * @param array $parameter
	 * @return string 
	 * @todo 兼容pgsql等其它数据库，pgsql的某些数据类型不接受单引号引起来的值
	 */
	public function bindParameter($sql, $parameter)
	{ 
		// 注意替换结果尾部加一个空格
		$sql = preg_replace("/:([a-zA-Z0-9_\-\x7f-\xff][a-zA-Z0-9_\-\x7f-\xff]*)\s*([,\)]?)/", "\x01\x02\x03\\1\x01\x02\x03\\2 ", $sql);
		$replacement = array();
        $find = array();
        foreach($parameter as $key => $value)
		{
			$find[] = "\x01\x02\x03$key\x01\x02\x03";
			if ($value instanceof LtDbSqlExpression)
			{
				$replacement[] = $value->__toString();
			}
            else if (is_null($value))
            {
                $replacement[] = 'NULL';
            }
			else if (is_string($value))
			{
				$replacement[] = "'" . $this->connectionAdapter->escape($value, $this->connectionResource) . "'";
			}
			else
			{
				$replacement[] = $value;
			}
		}
		$sql = str_replace($find, $replacement, $sql);
		return $sql;
	}

	/**
	 * get current sql adapter
	 * @return LtDbSqlAdapter
	 */
	protected function getCurrentSqlAdapter()
	{
		$factory = new LtDbAdapterFactory;
		$host = key($this->servers[$this->group][$this->node][$this->role]);
		return $factory->getSqlAdapter($this->servers[$this->group][$this->node][$this->role][$host]["sql_adapter"]);
	}

	/**
	 * select
	 * @param string $sql
	 * @param resource $connResource
	 * @return null|array
	 */
	protected function select($sql, $connResource)
	{
		$result = $this->connectionAdapter->query($sql, $connResource);
		if (empty($result))
		{
			return null;
		}
		else
		{
			return $result;
		}
	}

	/**
	 * insert
	 * @param string $sql
	 * @param LtDbConnectionAdapter $connResource
	 * @return int|boolean
	 */
	protected function insert($sql, $connResource)
	{
		if ($result = $this->connectionAdapter->exec($sql, $connResource))
		{
			return $this->connectionAdapter->lastInsertId($connResource);
		}
		else
		{
			return $result;
		}
	}

	/**
	 * change rows
	 * @param string $sql
	 * @param LtDbConnectionAdapter $connResource
	 * @return boolean|int
	 */
	protected function changeRows($sql, $connResource)
	{
		return $this->connectionAdapter->exec($sql, $connResource);
	}

	/**
	 * set session var
	 * @todo 更新连接缓存
	 * @param string $sql
	 * @param LtDbConnectionAdapter $connResource
	 * @return boolean
	 */
	protected function setSessionVar($sql, $connResource)
	{
		return false === $this->connectionAdapter->exec($sql, $connResource) ? false : true;
	}

	/**
	 * other
	 * @param string $sql
	 * @param LtDbConnectionAdapter $connResource
	 * @return boolean
	 */
	protected function other($sql, $connResource)
	{
		return false === $this->connectionAdapter->exec($sql, $connResource) ? false : true;
	}
}
