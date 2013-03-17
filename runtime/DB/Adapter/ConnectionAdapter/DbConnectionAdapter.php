<?php
/**
 * DB Adapter ConnectionAdapter
 * @author Jianxiang Qin <TalkativeDoggy@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause New BSD License
 * @version svn:$Id: DbConnectionAdapter.php 964 2012-08-27 04:02:32Z zhao5908@gmail.com $
 */

/**
 * interface LtDbConnectionAdapter
 * @author Jianxiang Qin <TalkativeDoggy@gmail.com>
 * @category runtime
 * @package   Lotusphp\DB\Adapter
 * @subpackage ConnectionAdapter
 */
interface LtDbConnectionAdapter
{
	/**
	 * connect
	 * @todo 兼容使用Unix Domain Socket方式连接数据库（即：可以不指定port）
	 * @param array $connConf
	 */
	public function connect($connConf);
	
	/**
	 * exec
	 * @param string $sql
	 * @param resource $connResource
	 */
	public function exec($sql, $connResource);
	
	/**
	 * query
	 * @param string $sql
	 * @param resource $connResource
	 */
	public function query($sql, $connResource);
	
	/**
	 * last insert id
	 * @param resource $connResource
	 */
	public function lastInsertId($connResource);
	
	/**
	 * escape
	 * @param string $sql
	 * @param resource $connResource
	 */
	public function escape($sql, $connResource);
}