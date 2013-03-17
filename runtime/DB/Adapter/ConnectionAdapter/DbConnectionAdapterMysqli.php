<?php
/**
 * DB Adapter ConnectionAdapter
 * @author Jianxiang Qin <TalkativeDoggy@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause New BSD License
 * @version svn:$Id: DbConnectionAdapterMysqli.php 964 2012-08-27 04:02:32Z zhao5908@gmail.com $
 */

/**
 * mysqli LtDbConnectionAdapter
 * @author Jianxiang Qin <TalkativeDoggy@gmail.com>
 * @category runtime
 * @package   Lotusphp\DB\Adapter
 * @subpackage ConnectionAdapter
 */
class LtDbConnectionAdapterMysqli implements LtDbConnectionAdapter
{
	/**
	 * connect
	 * @param array $connConf
	 * @return \mysqli
	 */
	public function connect($connConf)
	{
		return new mysqli($connConf["host"], $connConf["username"], $connConf["password"], $connConf["dbname"], $connConf["port"]);
	}

	/**
	 * exec
	 * @param string $sql
	 * @param resource $connResource
	 * @return boolean|array|int
	 */
	public function exec($sql, $connResource)
	{
		$connResource->query($sql);
		return $connResource->affected_rows;
	}

	/**
	 * query
	 * @param string $sql
	 * @param resource $connResource
	 * @return array
	 */
	public function query($sql, $connResource)
	{
		$rows = array();
		$result = $connResource->query($sql);
		while($row = $result->fetch_assoc())
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
		return $connResource->insert_id;
	}

	/**
	 * escape
	 * @param string $sql
	 * @param resource $connResource
	 * @return string
	 */
	public function escape($sql, $connResource)
	{
		return mysqli_real_escape_string($connResource, $sql);
	}
}