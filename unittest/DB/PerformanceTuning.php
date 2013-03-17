<?php
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . "common.inc.php";

require_once $lotusHome . "runtime/Cache/Cache.php";
require_once $lotusHome . "runtime/Cache/CacheAdapterFactory.php";
require_once $lotusHome . "runtime/Cache/CacheConfigBuilder.php";
require_once $lotusHome . "runtime/Cache/CacheConnectionManager.php";
require_once $lotusHome . "runtime/Cache/CacheHandle.php";
require_once $lotusHome . "runtime/Cache/Adapter/CacheAdapter.php";
require_once $lotusHome . "runtime/Cache/Adapter/CacheAdapterApc.php";
require_once $lotusHome . "runtime/Cache/Adapter/CacheAdapterEAccelerator.php";
require_once $lotusHome . "runtime/Cache/Adapter/CacheAdapterFile.php";
require_once $lotusHome . "runtime/Cache/Adapter/CacheAdapterMemcache.php";
require_once $lotusHome . "runtime/Cache/Adapter/CacheAdapterMemcached.php";
require_once $lotusHome . "runtime/Cache/Adapter/CacheAdapterXcache.php";
require_once $lotusHome . "runtime/Cache/QueryEngine/TableDataGateway/CacheTableDataGateway.php";

class PerformanceTuningDb extends PHPUnit_Framework_TestCase
{
	/**
	 * 本测试展示了如何用LtCache给LtDb提高性能
	 */
	public function testPerformance()
	{
		/**
		 * 配置数据库连接信息
		 */
		$dcb = new LtDbConfigBuilder;
		$dcb->addSingleHost(array("adapter" => "mysql", "username" => "test", "password" => "", "dbname" => "test"));

		/**
		 * 实例化组件入口类
		 */
		$db = new LtDb;
		$db->configHandle->addConfig(array("db.servers" => $dcb->getServers()));
		$db->init();

		/**
		 * 用法 1： 直接操作数据库

		 * 优点：学习成本低，快速入门

		 * 适用场景：
     1. 临时写个脚本操作数据库，不想花时间学习LtDb的查询引擎
     2. 只写少量脚本，不是一个完整持续的项目，不需要SqlMap来管理SQL语句
		 */
		$dbh = $db->getDbHandle();
		$dbh->query("DROP TABLE IF EXISTS test_user");
		$dbh->query("
			CREATE TABLE test_user (
			id INT NOT NULL AUTO_INCREMENT,
			name VARCHAR( 20 ) NOT NULL ,
			age INT NOT NULL ,
			PRIMARY KEY ( id ) 
		)");

		/**
		 * 用法 2： 使用Table Gateway查询引擎
		 * 
		 * 优点：自动生成SQL语句
		 * 
		 * 适用场景：
     1. 对数据表进行增简单的删查改操作，尤其是单条数据的操作
		 *      2. 简单的SELECT，动态合成WHERE子句
		 */
		$tg = $db->getTDG("test_user");

		/**
		 * 运行100次，要求在1秒内运行完
		 */
		$base_memory_usage = memory_get_usage();
		$times = 100;
		$startTime = microtime(true);

		for($i = 0; $i < $times; $i++)
		{
			$tg->insert(array("id" => $i, "name" => "lotusphp", "age" => 1));
		}
		$dbh->query("DROP TABLE IF EXISTS test_user");

		$endTime = microtime(true);
		$totalTime = round(($endTime - $startTime), 6);
		$averageTime = round(($totalTime / $times), 6);

		$memory_usage = memory_get_usage() - $base_memory_usage;
		$averageMemory = formatSize($memory_usage / $times);
		$memory_usage = formatSize($memory_usage);
		if (LOTUS_UNITTEST_DEBUG)
		{
			echo "\n----------------db getTDG insert----------------\n";
			echo "times      \t$times\n";
			echo "totalTime   \t{$totalTime}s\taverageTime   \t{$averageTime}s\n";
			echo "memoryUsage \t{$memory_usage}\taverageMemory \t{$averageMemory}";
			echo "\n---------------------------------------------------------\n";
		}
		$this->assertTrue(1 > $totalTime);
	}
	protected function setUp()
	{
	}
	protected function tearDown()
	{
	}
}
