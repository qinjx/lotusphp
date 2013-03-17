<?php
/**
 * Cache ConnectionManager
 * @author Jianxiang Qin <TalkativeDoggy@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause New BSD License
 * @version svn:$Id: CacheHandle.php 964 2012-08-27 04:02:32Z zhao5908@gmail.com $
 */

/**
 * 缓存 操作句柄
 * @author Jianxiang Qin <TalkativeDoggy@gmail.com>
 * @category runtime
 * @package   Lotusphp\Cache
 */
class LtCacheHandle
{
	/** @var LtConfig config handle */
	public $configHandle;
	
	/** @var string group */
	public $group;
	
	/** @var string node */
	public $node;
	
	/** @var string master */
	public $role = "master";
	
	/** @var LtCacheConnectionManager connection manager */
	public $connectionManager;
	
	/** @var resource connection resource */
	public $connectionResource;
	
	/** @var LtCacheAdapter connection adapter  */
	protected $connectionAdapter;

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
		$this->connectionManager = new LtCacheConnectionManager;
		$this->connectionManager->configHandle =$this->configHandle;
	}

	/**
	 * add
	 * @param string $key
	 * @param string|array|object $value
	 * @param int $ttl
	 * @param string $tableName
	 * @return boolean
	 */
	public function add($key, $value, $ttl = 0, $tableName = '')
	{
		$this->initConnection();
		return $this->connectionAdapter->add($key, $value, $ttl, $tableName, $this->connectionResource);
	}

	/**
	 * del
	 * @param string $key
	 * @param string $tableName
	 * @return boolean
	 */
	public function del($key, $tableName)
	{
		$this->initConnection();
		return $this->connectionAdapter->del($key, $tableName, $this->connectionResource);
	}

	/**
	 * get
	 * @param string $key
	 * @param string $tableName
	 * @return string|array|object
	 */
	public function get($key, $tableName)
	{
		$this->initConnection();
		return $this->connectionAdapter->get($key, $tableName, $this->connectionResource);
	}

	/**
	 * update
	 * @param string $key
	 * @param string|array|object $value
	 * @param int $ttl
	 * @param string $tableName
	 * @return boolean
	 */
	public function update($key, $value, $ttl = 0, $tableName = '')
	{
		$this->initConnection();
		return $this->connectionAdapter->update($key, $value, $ttl, $tableName, $this->connectionResource);
	}

	/**
	 * init connection
	 */
	protected function initConnection()
	{
		$connectionInfo = $this->connectionManager->getConnection($this->group, $this->node, $this->role);
		$this->connectionAdapter = $connectionInfo["connectionAdapter"];
		$this->connectionResource = $connectionInfo["connectionResource"];
	}
}