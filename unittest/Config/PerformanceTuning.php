<?php
/**
 * 本测试展示了如何用LtCache给LtConfig提高性能
 */
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

class PerformanceTuningConfig extends PHPUnit_Framework_TestCase
{
	public function testPerformance()
	{
		/**
		 * 用LtStoreFile作存储层提升性能
		 */
		$cacheHandle = new LtStoreFile;
		$cacheHandle->useSerialize = true;
		$cacheHandle->init(); 
		// 准备confif_file
		$config_file = dirname(__FILE__) . "/test_data/conf.php";

		/**
		 * 运行autoloader成功取到一个配置 
		 * 这是为了证明：使用LtCache作为LtConfig的存储，功能是正常的
		 */
		$conf = new LtConfig;
		$conf->storeHandle = $cacheHandle;
		$conf->loadConfigFile($config_file);
		$conf->init();
		$this->assertEquals("localhost", $conf->get("db.conn.host"));

		/**
		 * 运行100次，要求在1秒内运行完
		 */
		$base_memory_usage = memory_get_usage();
		$times = 100;
		$startTime = microtime(true);
		for($i = 0; $i < $times; $i++)
		{
			$conf->get('db.conn.host');
		}
		$endTime = microtime(true);
		$totalTime = round(($endTime - $startTime), 6);
		$averageTime = round(($totalTime / $times), 6);

		$memory_usage = memory_get_usage() - $base_memory_usage;
		$averageMemory = formatSize($memory_usage / $times);
		$memory_usage = formatSize($memory_usage);
		if (LOTUS_UNITTEST_DEBUG)
		{
			echo "\n----------------------config-----------------------------\n";
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
