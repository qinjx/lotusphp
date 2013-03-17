<?php
/**
 * DB Adapter ConnectionAdapter
 * @author Jianxiang Qin <TalkativeDoggy@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause New BSD License
 * @version svn:$Id: DbConnectionAdapterPdo.php 964 2012-08-27 04:02:32Z zhao5908@gmail.com $
 */

/**
 * PDO LtDbConnectionAdapter
 * @author Jianxiang Qin <TalkativeDoggy@gmail.com>
 * @category runtime
 * @package   Lotusphp\DB\Adapter
 * @subpackage ConnectionAdapter
 */
class LtDbConnectionAdapterPdo implements LtDbConnectionAdapter
{
	/**
	 * connect
	 * @param array $connConf
	 * @return \PDO
	 */
	public function connect($connConf)
	{
		// $option = array(PDO::ATTR_PERSISTENT => true);
		if (isset($connConf['pconnect']) && true == $connConf['pconnect'])
		{
			$option[PDO::ATTR_PERSISTENT] = true;
		}
		else
		{
			$option[PDO::ATTR_PERSISTENT] = false;
		}
		switch ($connConf['adapter'])
		{
			case "pdo_mysql":
				$dsn = "mysql:host={$connConf['host']};dbname={$connConf['dbname']}";
				break;
			case "pdo_sqlite":
				$connConf["host"] = rtrim($connConf["host"], '\\/') . DIRECTORY_SEPARATOR;
				if (!is_dir($connConf["host"]))
				{
					if (!@mkdir($connConf["host"], 0777, true))
					{
						trigger_error("Can not create {$connConf['host']}");
					}
				}
				$dsn = "{$connConf['sqlite_version']}:{$connConf['host']}{$connConf['dbname']}";
				break;
			case "pdo_pgsql":
				$dsn = "pgsql:host={$connConf['host']} port={$connConf['port']} dbname={$connConf['dbname']} user={$connConf['username']} password={$connConf['password']}";
				break;
			case "odbc":
				$dsn = "odbc:" . $connConf["host"];
				break;
		}
		return new PDO($dsn, $connConf['username'], $connConf['password'], $option);
	}

	/**
	 * exec
	 * @param string $sql
	 * @param resource $connResource
	 * @return boolean|array|int
	 */
	public function exec($sql, $connResource)
	{
		return $connResource->exec($sql);
	}

	/**
	 * query
	 * @param string $sql
	 * @param resource $connResource
	 * @return array
	 */
	public function query($sql, $connResource)
	{
		return $connResource->query($sql)->fetchAll(PDO::FETCH_ASSOC);
	}

	/**
	 * lasst insert id
	 * @todo pgsql support
	 * @param resource $connResource
	 * @return int
	 */
	public function lastInsertId($connResource)
	{
		return $connResource->lastInsertId();
	}

	/**
	 * escape
	 * @param string $sql
	 * @param resource $connResource
	 * @return string
	 */
	public function escape($sql, $connResource)
	{ 
		// quote返回值带最前面和最后面的单引号, 这里去掉, DbHandler中加
		return trim($connResource->quote($sql), "'");
	}
}
