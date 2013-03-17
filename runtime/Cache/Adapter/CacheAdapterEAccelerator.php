<?php
/**
 * CacheAdapterEAccelerator
 * @author Jianxiang Qin <TalkativeDoggy@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause New BSD License
 * @version svn:$Id: CacheAdapterEAccelerator.php 964 2012-08-27 04:02:32Z zhao5908@gmail.com $
 */

/**
 * 缓存 适配器 EAccelerator
 * @author Jianxiang Qin <TalkativeDoggy@gmail.com> Yi Zhao <zhao5908@gmail.com>
 * @category runtime
 * @package   Lotusphp\Cache\Adapter
 */
class LtCacheAdapterEAccelerator implements LtCacheAdapter
{
	/**
	 * connect
	 * @param null $hostConf
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
	public function add($key, $value, $ttl=0, $tableName = '', $connectionResource = null)
	{
		$connectionResource = null;
		$value = serialize($value); //eAccelerator doesn't serialize object
		return eaccelerator_put($this->getRealKey($tableName, $key), $value, $ttl);
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
		return eaccelerator_rm($this->getRealKey($tableName, $key));
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
		$value = eaccelerator_get($this->getRealKey($tableName, $key));
		if (!empty($value))
		{
			return unserialize($value);
		}
		else
		{
			return false;
		}
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