<?php
/**
 * DB Adapter SqlAdapter
 * @author Jianxiang Qin <TalkativeDoggy@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause New BSD License
 * @version svn:$Id: DbSqlAdapter.php 964 2012-08-27 04:02:32Z zhao5908@gmail.com $
 */

/**
 * interface LtDbSqlAdapter
 * @author Jianxiang Qin <TalkativeDoggy@gmail.com>
 * @category runtime
 * @package   Lotusphp\DB\Adapter
 * @subpackage SqlAdapter
 */
interface LtDbSqlAdapter
{
	/**
	 * set charset Return SQL statements
	 * @param string $charset
	 */
	public function setCharset($charset);
	
	/**
	 * set schema
	 * @param string $schema
	 */
	public function setSchema($schema);

	/**
	 * show schemas
	 * @param string $database
	 */
	public function showSchemas($database);
	
	/**
	 * show tables
	 * @param string $schema
	 */
	public function showTables($schema);
	
	/**
	 * show fields
	 * @param string $table
	 */
	public function showFields($table);

	/**
	 * begin transaction
	 */
	public function beginTransaction();
	
	/**
	 * commit
	 */
	public function commit();
	
	/**
	 * rellback
	 */
	public function rollBack();

	/**
	 * limit
	 * @param int $limit
	 * @param int $offset
	 */
	public function limit($limit, $offset);

	/**
	 * get schemas Retrive recordset
	 * @param string $queryResult
	 */
	public function getSchemas($queryResult);
	
	/**
	 * get tables
	 * @param string $queryResult
	 */
	public function getTables($queryResult);
	
	/**
	 * get fields
	 * @param string $queryResult
	 */
	public function getFields($queryResult);

	/**
	 * Parse SQL
	 * @param string $sql sql
	 */
	public function detectQueryType($sql);
}
