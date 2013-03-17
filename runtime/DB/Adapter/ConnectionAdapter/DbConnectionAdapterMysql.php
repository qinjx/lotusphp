<?php
/**
 * DB Adapter ConnectionAdapter
 * @author Jianxiang Qin <TalkativeDoggy@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause New BSD License
 * @version svn:$Id: DbConnectionAdapterMysql.php 964 2012-08-27 04:02:32Z zhao5908@gmail.com $
 */

/**
 * mysql LtDbConnectionAdapter
 * @author Jianxiang Qin <TalkativeDoggy@gmail.com>
 * @category runtime
 * @package   Lotusphp\DB\Adapter
 * @subpackage ConnectionAdapter
 */
class LtDbConnectionAdapterMysql implements LtDbConnectionAdapter
{
	/**
	 * connect
	 * @param array $connConf
	 * @return resource
	 */
	public function connect($connConf)
	{
		return mysql_connect($connConf["host"] . ":" . $connConf["port"], $connConf["username"], $connConf["password"]);
	}

	/**
	 * exec
	 * @param string $sql
	 * @param resource $connResource
	 * @return boolean|array|int
	 */
	public function exec($sql, $connResource)
	{
		return mysql_query($sql, $connResource) ? mysql_affected_rows($connResource) : false;
	}

	/**
	 * query
	 * @param string $sql
	 * @param resource $connResource
	 * @return array
	 */
	public function query($sql, $connResource)
	{
		$result = mysql_query($sql, $connResource);
		$rows = array();
		while($row = mysql_fetch_assoc($result))
		{
			$rows[] = $row;
		}
		return $rows;
	}

	/**
	 * last insert id
	 * @param resource $connResource
	 * @return int
	 */
	public function lastInsertId($connResource)
	{
		return mysql_insert_id($connResource);
	}

	/**
	 * escape
	 * @param string $sql
	 * @param resource $connResource
	 * @return string
	 */
	public function escape($sql, $connResource)
	{
		return mysql_real_escape_string($sql, $connResource);
	}
}