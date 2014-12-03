<?php
/**
 * DB factory
 * @author Jianxiang Qin <TalkativeDoggy@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause New BSD License
 * @version svn:$Id: DbAdapterFactory.php 964 2012-08-27 04:02:32Z zhao5908@gmail.com $
 */

/**
 * adapter factory
 * @author Jianxiang Qin <TalkativeDoggy@gmail.com>
 * @category runtime
 * @package   Lotusphp\DB
 */
class LtDbAdapterFactory
{
	/**
	 * get connection adapter
	 * @param string $connectionAdapterType
	 * @return \LtDbConnectionAdapter
	 */
	public function getConnectionAdapter($connectionAdapterType)
	{
		$LtDbConnectionAdapter = "LtDbConnectionAdapter" . ucfirst($connectionAdapterType);
		return new $LtDbConnectionAdapter;
	}

	/**
	 * get sql adapter
	 * @param string $sqlAdapterType
	 * @return \LtDbSqlAdapter
	 */
	public function getSqlAdapter($sqlAdapterType)
	{
		$LtDbSqlAdapter = "LtDbSqlAdapter" . ucfirst($sqlAdapterType);
		return new $LtDbSqlAdapter;
	}
}