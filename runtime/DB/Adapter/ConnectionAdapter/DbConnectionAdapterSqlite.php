<?php
/**
 * DB Adapter ConnectionAdapter
 * @author Yi Zhao <zhao5908@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause New BSD License
 * @version svn:$Id: DbConnectionAdapterSqlite.php 964 2012-08-27 04:02:32Z zhao5908@gmail.com $
 */

/**
 * sqlite 2 LtDbConnectionAdapter
 * @author Yi Zhao <zhao5908@gmail.com>
 * @category runtime
 * @package   Lotusphp\DB\Adapter
 * @subpackage ConnectionAdapter
 */
class LtDbConnectionAdapterSqlite implements LtDbConnectionAdapter
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
			$func = 'sqlite_popen';
		} 
		else
		{
			$func = 'sqlite_open';
		} 
		$connConf["host"] = rtrim($connConf["host"], '\\/') . DIRECTORY_SEPARATOR;
		if(!is_dir($connConf["host"]))
		{
			if(!@mkdir($connConf["host"], 0777, true))
			{
				trigger_error("Can not create {$connConf['host']}");
			}
		}
		$error = '';
		$connResource = $func($connConf["host"] . $connConf["dbname"], 0666, $error);
		if (!$connResource)
		{
			trigger_error($error, E_USER_ERROR);
		} 
		else
		{
			return $connResource;
		} 
	} 

	/**
	 * exec
	 * @param string $sql
	 * @param resource $connResource
	 * @return int
	 */
	public function exec($sql, $connResource)
	{
		if(empty($sql))
		{
			return 0;
		}
		sqlite_exec($connResource, $sql); 
		// echo '<pre>';
		// print_r(debug_backtrace());
		// debug_print_backtrace();
		// echo '</pre>';
		// delete from table 结果为0，原因未知。
		// 使用 delete from table where 1 能返回正确结果
		return sqlite_changes($connResource);
	} 

	/**
	 * query
	 * @param string $sql
	 * @param resource $connResource
	 * @return array|boolean
	 */
	public function query($sql, $connResource)
	{
		$result = sqlite_query($connResource, $sql, SQLITE_ASSOC);
		return sqlite_fetch_all($result, SQLITE_ASSOC);
	} 

	/**
	 * last insert id
	 * @param resource $connResource
	 * @return int
	 */
	public function lastInsertId($connResource)
	{
		return sqlite_last_insert_rowid($connResource);
	} 

	/**
	 * escape
	 * @param string $sql
	 * @param resource $connResource
	 * @return string
	 */
	public function escape($sql, $connResource)
	{
		return sqlite_escape_string($sql);
	} 
} 
