<?php
/**
 * CacheAdapter
 * @author Jianxiang Qin <TalkativeDoggy@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause New BSD License
 * @version svn:$Id: CacheAdapter.php 964 2012-08-27 04:02:32Z zhao5908@gmail.com $
 */

/**
 * 缓存 适配器 接口
 * @author Jianxiang Qin <TalkativeDoggy@gmail.com>
 * @category runtime
 * @package   Lotusphp\Cache\Adapter
 */
interface LtCacheAdapter
{
	/**
	 * connect
	 * @param array $hostConf
	 */
	public function connect($hostConf);
	/**
	 * add
	 * @param string $key
	 * @param string|array|object $value
	 * @param int $ttl
	 * @param string $tableName
	 * @param resource $connectionResource
	 */
	public function add($key, $value, $ttl = 0, $tableName = '', $connectionResource = NULL);
	/**
	 * del
	 * @param string $key
	 * @param string $tableName
	 * @param resource $connectionResource
	 */
	public function del($key, $tableName, $connectionResource);
	/**
	 * get
	 * @param string $key
	 * @param string $tableName
	 * @param resource $connectionResource
	 */
	public function get($key, $tableName, $connectionResource);
	/**
	 * update
	 * @param string $key
	 * @param string|array|object $value
	 * @param int $ttl
	 * @param string $tableName
	 * @param resource $connectionResource
	 */
	public function update($key, $value, $ttl = 0, $tableName = '', $connectionResource = NULL);
}