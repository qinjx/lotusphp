<?php
/**
 * DB Adapter SqlAdapter
 * @author Yi Zhao <zhao5908@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause New BSD License
 * @version svn:$Id: DbSqlAdapterSqlite.php 964 2012-08-27 04:02:32Z zhao5908@gmail.com $
 */

/**
 * sqlite LtDbSqlAdapter
 * @author Yi Zhao <zhao5908@gmail.com>
 * @category runtime
 * @package   Lotusphp\DB\Adapter
 * @subpackage SqlAdapter
 */
class LtDbSqlAdapterSqlite implements LtDbSqlAdapter
{
	/**
	 * set charset
	 * @param string $charset
	 * @return string
	 */
	public function setCharset($charset)
	{
		// return 'PRAGMA encoding = "' . $charset . '"';
		return '';
	}
	
	/**
	 * set schema
	 * @param string $schema
	 * @return string
	 */
	public function setSchema($schema)
	{
		return '';
	}

	/**
	 * begin transaction
	 * @return string
	 */
	public function beginTransaction()
	{
		return 'BEGIN TRANSACTION';
	}

	/**
	 * commit
	 * @return string
	 */
	public function commit()
	{
		return 'COMMIT TRANSACTION';
	}

	/**
	 * rolllback
	 * @return string
	 */
	public function rollBack()
	{
		return 'ROLLBACK TRANSACTION';
	}

	/**
	 * show schemas
	 * @param string $database
	 * @return string
	 */
	public function showSchemas($database)
	{
		//return "SHOW DATABASES";
		return '';
	}
	
	/**
	 * show tables
	 * @param string $schema
	 * @return string
	 */
	public function showTables($schema)
	{
		// 临时表及其索引不在 SQLITE_MASTER 表中而在 SQLITE_TEMP_MASTER 中出现
		return "SELECT name FROM sqlite_master WHERE type='table' UNION ALL SELECT name FROM sqlite_temp_master WHERE type='table' ORDER BY name";
	}
	
	/**
	 * show fields
	 * @param string $table
	 * @return string
	 */
	public function showFields($table)
	{
		return "PRAGMA table_info('" . $table . "')";

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
	 * @return array
	 */
	public function getTables($queryResult)
	{
		return $queryResult;
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
			// 字段名
			$fields[$value['name']]['name'] = $value['name'];
			// 字段类型
			$fulltype = $value['type'];
			$size = null;
			$precision = null;
			$scale = null;

			if (preg_match('/^([^\(]+)\(\s*(\d+)\s*,\s*(\d+)\s*\)$/',$fulltype, $matches))
			{
				$type = $matches[1];
				$precision = $matches[2];
				$scale = $matches[3]; // aka precision
			}
			elseif (preg_match('/^([^\(]+)\(\s*(\d+)\s*\)$/',$fulltype, $matches))
			{
				$type = $matches[1];
				$size = $matches[2];
			}
			else
			{
				$type = $fulltype;
			}

			$fields[$value['name']]['type'] = $type;
			/**
			* not null is 99, null is 0
			*/
			$fields[$value['name']]['notnull'] = (bool) ($value['notnull'] != 0);
			$fields[$value['name']]['default'] = $value['dflt_value'];
			$fields[$value['name']]['primary'] = (bool) ($value['pk'] == 1 && strtoupper(substr($fulltype, 0, 7)) == 'INTEGER');
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
		if (preg_match("/^\s*SELECT|^\s*PRAGMA/i", $sql))
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
