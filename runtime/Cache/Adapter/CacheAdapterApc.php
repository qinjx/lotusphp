<?php
/**
 * CacheAdapterApc
 * @author Jianxiang Qin <TalkativeDoggy@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause New BSD License
 * @version svn:$Id: CacheAdapterApc.php 964 2012-08-27 04:02:32Z zhao5908@gmail.com $
 */

/**
 * 缓存 适配器 APC
 * @author Jianxiang Qin <TalkativeDoggy@gmail.com>
 * @category runtime
 * @package   Lotusphp\Cache\Adapter
 */
class LtCacheAdapterApc implements LtCacheAdapter
{
	/**
	 * connect
	 * @param array $hostConf
	 * @return boolean
	 */
	public function connect($hostConf)
	{
		$hostConf = null;
		return true;
	}

	/**
	 * add
	 * @param string $key
	 * @param string|array|object $value
	 * @param int $ttl
	 * @param string $tableName
	 * @param resource $connectionResource
	 * @return boolean
	 */
	public function add($key, $value, $ttl = 0, $tableName = '', $connectionResource = null)
	{
		$connectionResource = null;
		return apc_add($this->getRealKey($tableName, $key), $value, $ttl);
	}

	/**
	 * del
	 * @param string $key
	 * @param string $tableName
	 * @param resource $connectionResource
	 * @return boolean
	 */
	public function del($key, $tableName, $connectionResource)
	{
		$connectionResource = null;
		return apc_delete($this->getRealKey($tableName, $key));
	}

	/**
	 * get
	 * @param string $key
	 * @param string $tableName
	 * @param resource $connectionResource
	 * @return string|array|object
	 */
	public function get($key, $tableName, $connectionResource)
	{
		$connectionResource = null;
		return apc_fetch($this->getRealKey($tableName, $key));
	}

	/**
	 * update
	 * @param string $key
	 * @param string|array|object $value
	 * @param int $ttl
	 * @param string $tableName
	 * @param resource $connectionResource
	 * @return boolean
	 */
	public function update($key, $value, $ttl = 0, $tableName = '', $connectionResource = null)
	{
		if ($this->del($key, $tableName, $connectionResource))
		{
			return $this->add($key, $value, $ttl, $tableName, $connectionResource);
		}
		else
		{
			return false;
		}
	}

	/**
	 * Hash
	 * @param string $tableName
	 * @param string $key
	 * @return string
	 */
	protected function getRealKey($tableName, $key)
	{
		return $tableName . "-" . $key;
	}
}