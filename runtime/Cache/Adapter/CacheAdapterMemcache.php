<?php
/**
 * CacheAdapterMemcache
 * @author Jianxiang Qin <TalkativeDoggy@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause New BSD License
 * @version svn:$Id: CacheAdapterMemcache.php 964 2012-08-27 04:02:32Z zhao5908@gmail.com $
 */

/**
 * 缓存 适配器 Memcache
 * @author Jianxiang Qin <TalkativeDoggy@gmail.com>
 * @category runtime
 * @package   Lotusphp\Cache\Adapter
 */
class LtCacheAdapterMemcache implements LtCacheAdapter
{
	/**
	 * connect
	 * @param array $hostConf
	 */
	public function connect($hostConf)
	{
		memcache_connect($hostConf["host"], $hostConf["port"]);
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
		return $connectionResource->add($this->getRealKey($tableName, $key), $value, false, $ttl);
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
		return $connectionResource->delete($this->getRealKey($tableName, $key), 0);
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
		return $connectionResource->get($this->getRealKey($tableName, $key));
	}

	/**
	 * update
	 * @param string $key
	 * @param mixed $value
	 * @param int $ttl
	 * @param string $tableName
	 * @param resource $connectionResource
	 * @return boolean
	 */
	public function update($key, $value, $ttl = 0, $tableName = '', $connectionResource = null)
	{
		return $connectionResource->replace($this->getRealKey($tableName, $key), $value, false, $ttl);
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