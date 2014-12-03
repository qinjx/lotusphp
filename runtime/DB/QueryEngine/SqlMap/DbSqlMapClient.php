<?php
/**
 * LtDbSqlMapClient
 * @author Jianxiang Qin <TalkativeDoggy@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause New BSD License
 * @version svn:$Id: DbSqlMapClient.php 964 2012-08-27 04:02:32Z zhao5908@gmail.com $
 */

/**
 * LtDbSqlMapClient
 * @author Jianxiang Qin <TalkativeDoggy@gmail.com>
 * @category runtime
 * @package   Lotusphp\DB\QueryEngine
 * @subpackage SqlMap
 */
class LtDbSqlMapClient
{
	/** @var LtConfig config handle */
	public $configHandle;
	
	/** @var LtDbHandle db handle */
	public $dbh;

	/**
	 * execute
	 * @param string $mapId
	 * @param array $bind
	 * @return boolean|array|int|object
	 */
	public function execute($mapId, $bind = null)
	{
		$sqlMap = $this->configHandle->get("db.sqlmap." . $this->dbh->group . "." . $mapId);
		$forceUseMaster = isset($sqlMap["force_use_master"]) ? $sqlMap["force_use_master"] : false;
		return $this->dbh->query($sqlMap["sql"], $bind, $forceUseMaster);
	}
}