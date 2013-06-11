<?php
/**
 * DB Adapter SqlAdapter
 * @author Jianxiang Qin <TalkativeDoggy@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause New BSD License
 * @version svn:$Id: DbSqlAdapterMysql.php 964 2012-08-27 04:02:32Z zhao5908@gmail.com $
 */

/**
 * mysql LtDbSqlAdapter
 * @author Jianxiang Qin <TalkativeDoggy@gmail.com>
 * @category runtime
 * @package   Lotusphp\DB\Adapter
 * @subpackage SqlAdapter
 */
class LtDbSqlAdapterMysql implements LtDbSqlAdapter
{
	/**
	 * set charset
	 * @param string $charset
	 * @return string
	 */
	public function setCharset($charset)
	{
		return "SET NAMES " . str_replace('-', '', $charset);
	}
	
	/**
	 * set schema
	 * @param string $schema
	 * @return string
	 */
	public function setSchema($schema)
	{
		return "USE $schema";
	}

	/**
	 * show schemas
	 * @param string $database
	 * @return string
	 */
	public function showSchemas($database)
	{
		return "SHOW DATABASES";
	}
	
	/**
	 * show tables
	 * @param string $schema
	 * @return string
	 */
	public function showTables($schema)
	{
		return "SHOW TABLES";
	}
	
	/**
	 * show fields
	 * @param string $table
	 * @return string
	 */
	public function showFields($table)
	{
		return "DESCRIBE $table";
	}

	/**
	 * begin transaction
	 * @return string
	 */
	public function beginTransaction()
	{
		return "START TRANSACTION";
	}
	
	/**
	 * commit
	 * @return string
	 */
	public function commit()
	{
		return "COMMIT";
	}
	
	/**
	 * rollback
	 * @return string
	 */
	public function rollBack()
	{
		return "ROLLBACK";
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
	 * @return array
	 */
	public function getFields($queryResult)
	{
		$fields = array();
        foreach ($queryResult as $value)
        {
			$fields[$value['Field']]['name'] = $value['Field'];
			$fields[$value['Field']]['type'] = $value['Type'];
			/*
			 * not null is NO or empty, null is YES
			 */
			$fields[$value['Field']]['notnull'] = (bool) ($value['Null'] != 'YES');
			$fields[$value['Field']]['default'] = $value['Default'];
			$fields[$value['Field']]['primary'] = (strtolower($value['Key']) == 'pri');
		}
		return $fields;
	}
	
	/**
	 * detect query type
	 * @param string $sql
	 * @return string
	 */
	public function detectQueryType($sql)
	{
		if (preg_match("/^\s*SELECT|^\s*EXPLAIN|^\s*SHOW|^\s*DESCRIBE/i", $sql))
		{
			$ret = 'SELECT';
		}
		else if (preg_match("/^\s*INSERT/i", $sql))
		{
			$ret = 'INSERT';
		}
		else if (preg_match("/^\s*UPDATE|^\s*DELETE|^\s*REPLACE/i", $sql))
		{
			$ret = 'CHANGE_ROWS';
		}
		else if (preg_match("/^\s*USE|^\s*SET/i", $sql))
		{
			$ret = 'SET_SESSION_VAR';
		}
		else
		{
			$ret = 'OTHER';
		}
		return $ret;
	}
}