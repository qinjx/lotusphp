<?php
/**
 * 本测试展示了如何用LtCache给LtAutoloader提高性能
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

class PerformanceTuningAutoloader extends PHPUnit_Framework_TestCase
{
	public function testPerformance()
	{
		// 准备autoloadPath
		$autoloadPath = array(
			dirname(__FILE__) . "/test_data/class_dir_1",
			dirname(__FILE__) . "/test_data/class_dir_2",
			dirname(__FILE__) . "/test_data/function_dir_1",
			dirname(__FILE__) . "/test_data/function_dir_2",
			);
		/**
		 * 用LtStoreFile作存储层提升性能
		 */
		$cacheHandle = new LtStoreFile;
		$prefix = sprintf("%u", crc32(serialize($autoloadPath)));
		$cacheHandle->prefix = 'Lotus-' . $prefix;
		$cacheHandle->init(); 
		/**
		 * 运行autoloader成功加载一个类
		 * 这是为了证明：使用LtCache作为LtAutoloader的存储，功能是正常的
		 */
		$autoloader = new LtAutoloader;
		$autoloader->devMode = false; // 关闭开发模式
		$autoloader->storeHandle = $cacheHandle;
		$autoloader->autoloadPath = $autoloadPath;
		$autoloader->init();
		$this->assertTrue(class_exists("HelloWorld"));

		/**
		 * 运行1500次，要求在1秒内运行完
		 */
		$base_memory_usage = memory_get_usage();
		$times = 1500;
		$startTime = microtime(true);
		for($i = 0; $i < $times; $i++)
		{
			$autoloader = new LtAutoloader;
			$autoloader->devMode = false; // 关闭开发模式
			$autoloader->storeHandle = $cacheHandle;
			$autoloader->autoloadPath = $autoloadPath;
			$autoloader->init();
			unset($autoloader);
		}
		$endTime = microtime(true);
		$totalTime = round(($endTime - $startTime), 6);
		$averageTime = round(($totalTime / $times), 6);

		$memory_usage = memory_get_usage() - $base_memory_usage;
		$averageMemory = formatSize($memory_usage / $times);
		$memory_usage = formatSize($memory_usage);
		if (LOTUS_UNITTEST_DEBUG)
		{
			echo "\n---------------------autoloader--------------------------\n";
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
