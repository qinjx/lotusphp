<?php
/**
 * CacheAdapterFile
 * @author Jianxiang Qin <TalkativeDoggy@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause New BSD License
 * @version svn:$Id: CacheAdapterFile.php 964 2012-08-27 04:02:32Z zhao5908@gmail.com $
 */

/**
 * 缓存 适配器 File
 * @author Jianxiang Qin <TalkativeDoggy@gmail.com>
 * @category runtime
 * @package   Lotusphp\Cache\Adapter
 */
class LtCacheAdapterFile implements LtCacheAdapter
{
	/**
	 * connect
	 * @param array $hostConf
	 * @return \LtStoreFile
	 */
	public function connect($hostConf)
	{
		$fileStore = new LtStoreFile;
		$fileStore->prefix = 'LtCache-file';
		if (isset($hostConf['host']))
		{
			$fileStore->storeDir = $hostConf["host"];
		}
		$fileStore->init();
		return $fileStore;
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
		if (0 != $ttl)
		{
			$ttl += time();
		}
		if (true == $connectionResource->add($this->getRealKey($tableName, $key), array("ttl" => $ttl, "value" => $value)))
		{
			return true;
		}
		else
		{
			if ($this->get($key,$tableName,$connectionResource))
			{
				return false;
			}
			else
			{
				$this->del($key,$tableName,$connectionResource);
				return $connectionResource->add($this->getRealKey($tableName, $key), array("ttl" => $ttl, "value" => $value));
			}
		}
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
		return $connectionResource->del($this->getRealKey($tableName, $key));
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
		$cachedArray = $connectionResource->get($this->getRealKey($tableName, $key));
		if (is_array($cachedArray) && (0 == $cachedArray["ttl"] || $cachedArray["ttl"] > time()))
		{
			return $cachedArray["value"];
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
		if (0 != $ttl)
		{
			$ttl += time();
		}
		return $connectionResource->update($this->getRealKey($tableName, $key), array("ttl" => $ttl, "value" => $value));
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
