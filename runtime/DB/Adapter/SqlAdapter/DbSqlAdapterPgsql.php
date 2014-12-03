<?php
/**
 * DB Adapter SqlAdapter
 * @author Jianxiang Qin <TalkativeDoggy@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause New BSD License
 * @version svn:$Id: DbSqlAdapterPgsql.php 964 2012-08-27 04:02:32Z zhao5908@gmail.com $
 */

/**
 * PostgreSQL LtDbSqlAdapter
 * @author Jianxiang Qin <TalkativeDoggy@gmail.com>
 * @category runtime
 * @package   Lotusphp\DB\Adapter
 * @subpackage SqlAdapter
 */
class LtDbSqlAdapterPgsql implements LtDbSqlAdapter
{
	/**
	 * set charset
	 * @param string $charset
	 * @return string
	 */
	public function setCharset($charset)
	{
		return "SET client_encoding TO '$charset'";
	}
	
	/**
	 * set schema
	 * @param string $schema
	 * @return string
	 */
	public function setSchema($schema)
	{
		return "SET search_path TO $schema";
	}

	/**
	 * begin transaction
	 * @return string
	 */
	public function beginTransaction()
	{
		return "";
	}
	
	/**
	 * commit
	 * @return string
	 */
	public function commit()
	{
		return "";
	}
	
	/**
	 * rollback
	 * @return string
	 */
	public function rollBack()
	{
		return "";
	}

	/**
	 * show schemas
	 * @param string $database
	 */
	public function showSchemas($database)
	{

	}
	
	/**
	 * show tables
	 * @param string $schema
	 * @return string
	 */
	public function showTables($schema)
	{
		return "SELECT case when n.nspname='public' then c.relname else n.nspname||'.'||c.relname end as relname 
				FROM pg_class c join pg_namespace n on (c.relnamespace=n.oid)
				WHERE c.relkind = 'r'
					AND n.nspname NOT IN ('information_schema','pg_catalog')
					AND n.nspname NOT LIKE 'pg_temp%'
					AND n.nspname NOT LIKE 'pg_toast%'
				ORDER BY relname";
	}
	
	/**
	 * show fields
	 * @param string $table
	 * @return string
	 */
	public function showFields($table)
	{
		return "SELECT a.attnum, a.attname AS field, t.typname AS type, 
				format_type(a.atttypid, a.atttypmod) AS complete_type, 
				a.attnotnull AS isnotnull, 
				( SELECT 't' FROM pg_index 
				WHERE c.oid = pg_index.indrelid 
				AND pg_index.indkey[0] = a.attnum 
				AND pg_index.indisprimary = 't') AS pri, 
				(SELECT pg_attrdef.adsrc FROM pg_attrdef 
				WHERE c.oid = pg_attrdef.adrelid 
				AND pg_attrdef.adnum=a.attnum) AS default 
				FROM pg_attribute a, pg_class c, pg_type t 
				WHERE c.relname = '$table' 
				AND a.attnum > 0 
				AND a.attrelid = c.oid 
				AND a.atttypid = t.oid 
				ORDER BY a.attnum";
	}

	/**
	 * limit
	 * @param int $limit
	 * @param int $offset
	 * @return string
	 */
	public function limit($limit, $offset)
	{
		return " LIMIT $limit OFFSET $offset";
	}

	/**
	 * get schemas
	 * @param array $queryResult
	 */
	public function getSchemas($queryResult)
	{
		
	}
	
	/**
	 * get tables
	 * @param array $queryResult
	 */
	public function getTables($queryResult)
	{
		
	}
	
	/**
	 * get fields
	 * @param array $queryResult
	 */
	public function getFields($queryResult)
	{
		
	}
	
	/**
	 * detect query type
	 * @param string $sql
	 */
	public function detectQueryType($sql)
	{
		
	}
}