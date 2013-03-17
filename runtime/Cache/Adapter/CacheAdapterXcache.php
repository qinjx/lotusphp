<?php
/**
 * CacheAdapterXcache
 * @author Jianxiang Qin <TalkativeDoggy@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause New BSD License
 * @version svn:$Id: CacheAdapterXcache.php 964 2012-08-27 04:02:32Z zhao5908@gmail.com $
 */

/**
 * 缓存 适配器 Xcache
 * @author Jianxiang Qin <TalkativeDoggy@gmail.com>
 * @category runtime
 * @package   Lotusphp\Cache\Adapter
 */
class LtCacheAdapterXcache implements LtCacheAdapter
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
		return xcache_set($this->getRealKey($tableName, $key), $value, $ttl);
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
		return xcache_unset($this->getRealKey($tableName, $key));
	}

	/**
	 * get
	 * @param string $key
	 * @param string $tableName
	 * @param resource $connectionResource
	 * @return boolean
	 */
	public function get($key, $tableName, $connectionResource)
	{
		$connectionResource = null;
		$key = $this->getRealKey($tableName, $key);
		if (xcache_isset($key))
		{
			return xcache_get($key);
		}
		return false;
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
		$connectionResource = null;
		$key = $this->getRealKey($tableName, $key);
		if (xcache_isset($key))
		{
			return xcache_set($key, $value, $ttl);
		}
		return false;
	}

	/**
	 * hash
	 * @param string $tableName
	 * @param string $key
	 * @return string
	 */
	protected function getRealKey($tableName, $key)
	{
		return $tableName . "-" . $key;
	}
}
