<?php
/**
 * CacheAdapterMemcached
 * @author Jianxiang Qin <TalkativeDoggy@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause New BSD License
 * @version svn:$Id: CacheAdapterMemcached.php 964 2012-08-27 04:02:32Z zhao5908@gmail.com $
 */

/**
 * 缓存 适配器 Memcached
 * @author Jianxiang Qin <TalkativeDoggy@gmail.com>
 * @category runtime
 * @package   Lotusphp\Cache\Adapter
 */
class LtCacheAdapterMemcached implements LtCacheAdapter
{
	/**
	 * connect
	 * @param array $hostConf
	 * @return \Memcached
	 */
	public function connect($hostConf)
	{
		$connectionResource = new Memcached();
		$connectionResource->addServer($hostConf["host"], $hostConf["port"]);
		return $connectionResource;
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
	public function add($key, $value, $ttl=0, $tableName = '', $connectionResource = null)
	{
		return $connectionResource->add($this->getRealKey($tableName, $key), $value, $ttl);
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
		return $connectionResource->delete($this->getRealKey($tableName, $key));
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
	 * @param string|array|object $value
	 * @param int $ttl
	 * @param string $tableName
	 * @param resource $connectionResource
	 * @return boolean
	 */
	public function update($key, $value, $ttl = 0, $tableName = '', $connectionResource = null)
	{
		return $connectionResource->replace($this->getRealKey($tableName, $key), $value, $ttl);
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