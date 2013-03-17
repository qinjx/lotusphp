<?php
/**
 * DB Adapter ConnectionAdapter
 * @author Jianxiang Qin <TalkativeDoggy@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause New BSD License
 * @version svn:$Id: DbConnectionAdapterPgsql.php 964 2012-08-27 04:02:32Z zhao5908@gmail.com $
 */

/**
 * PostgreSQL LtDbConnectionAdapter
 * @author Jianxiang Qin <TalkativeDoggy@gmail.com>
 * @category runtime
 * @package   Lotusphp\DB\Adapter
 * @subpackage ConnectionAdapter
 */
class LtDbConnectionAdapterPgsql implements LtDbConnectionAdapter
{
	/**
	 * connect
	 * @param array $connConf
	 * @return resource
	 */
	public function connect($connConf)
	{
		if (isset($connConf['pconnect']) && true == $connConf['pconnect'])
		{
			$func = 'pg_pconnect';
		}
		else
		{
			$func = 'pg_connect';
		}
		return $func("host={$connConf['host']} port={$connConf['port']} user={$connConf['username']} password={$connConf['password']}");
	}

	/**
	 * exec
	 * @param string $sql
	 * @param resource $connResource
	 * @return array|boolean|int
	 */
	public function exec($sql, $connResource)
	{
		$result = pg_query($connResource, $sql);
		return pg_affected_rows($result);
	}

	/**
	 * query
	 * @param string $sql
	 * @param resource $connResource
	 * @return array
	 */
	public function query($sql, $connResource)
	{
		$result = pg_query($connResource, $sql);
		return pg_fetch_all($result);
	}

	// SELECT CURRVAL(
	// pg_get_serial_sequence('my_tbl_name','id_col_name'));"
	// ------------------------------------------------------
	// CREATE FUNCTION last_insert_id() RETURNS bigint AS $$
	// SELECT lastval();
	// $$ LANGUAGE SQL VOLATILE;
	/**
	 * last insert id
	 * @param resource $connResource
	 * @return int
	 */
	public function lastInsertId($connResource)
	{
		$result = pg_query($connResource, "SELECT lastval()");
		$row = pg_fetch_array($result, 0, PGSQL_NUM);
		return $row[0];
	}

	/**
	 * escape
	 * @param string $sql
	 * @param resource $connResource
	 * @return string
	 */
	public function escape($sql, $connResource)
	{
		return pg_escape_string($sql);
	}
}
