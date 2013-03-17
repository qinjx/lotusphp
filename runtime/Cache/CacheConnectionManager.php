<?php
/**
 * Cache ConnectionManager
 * @author Jianxiang Qin <TalkativeDoggy@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause New BSD License
 * @version svn:$Id: CacheConnectionManager.php 964 2012-08-27 04:02:32Z zhao5908@gmail.com $
 */

/**
 * 缓存 连接管理器
 * @author Jianxiang Qin <TalkativeDoggy@gmail.com>
 * @category runtime
 * @package   Lotusphp\Cache
 */
class LtCacheConnectionManager
{
	/** @var LtConfig config handle */
	public $configHandle;
	
	/** @var LtCacheAdapter 链接适配器实例 */
	protected $connectionAdapter;

	/**
	 * 返回适配器名称和连接资源
	 * @param string $group
	 * @param string $node
	 * @param string $role
	 * @return boolean|array
	 */
	public function getConnection($group, $node, $role)
	{
		if ($connection = $this->getNewConnection($group, $node, $role))
		{
			return array(
				"connectionAdapter" => $this->connectionAdapter,
				"connectionResource" => $connection
			);
		}
		else
		{
			trigger_error("no cache server can be connected");
			return false;
		}
	}

	/**
	 * get new connection
	 * @param string $group
	 * @param string $node
	 * @param string $role
	 * @return boolean|resource
	 */
	protected function getNewConnection($group, $node, $role)
	{
		$servers = $this->configHandle->get("cache.servers");
		$hostTotal = count($servers[$group][$node][$role]);
		$hostIndexArray = array_keys($servers[$group][$node][$role]);
		while ($hostTotal)
		{
			$hashNumber = substr(microtime(),7,1) % $hostTotal;
			$hostConfig = $servers[$group][$node][$role][$hostIndexArray[$hashNumber]];
			$cacheFactory = new LtCacheAdapterFactory;
			$this->connectionAdapter = $cacheFactory->getConnectionAdapter($hostConfig["adapter"]);
			if ($connection = $this->connectionAdapter->connect($hostConfig))
			{
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
}