<?php
/**
 * url生成, 性能测试
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

class PerformanceTuningUrl extends PHPUnit_Framework_TestCase
{
	public function testPerformance()
	{ 
		$config['router.routing_table']['protocol'] = 'rewrite';
		// 初始化LtUrl
		$url = new LtUrl;
		$url->configHandle->addConfig($config);
		$url->init(); 
		// 初始化结束
		// 测试生成超链接
		$href = $url->generate('news', 'list', array('catid' => 4, 'page' => 10));
		$this->assertEquals('/news-list-catid-4-page-10.html', $href);

		/**
		 * 运行 10,000 次，要求在1秒内运行完
		 */
		$base_memory_usage = memory_get_usage();
		$times = 10000;
		$startTime = microtime(true);
		for($i = 0; $i < $times; $i++)
		{
			$url->generate('news', 'list', array('catid' => 4, 'page' => 10));
		}
		$endTime = microtime(true);
		$totalTime = round(($endTime - $startTime), 6);
		$averageTime = round(($totalTime / $times), 6);

		$memory_usage = memory_get_usage() - $base_memory_usage;
		$averageMemory = formatSize($memory_usage / $times);
		$memory_usage = formatSize($memory_usage);
		if (LOTUS_UNITTEST_DEBUG)
		{
			echo "\n----------------------Url-----------------------------\n";
			echo "times      \t$times\n";
			echo "totalTime   \t{$totalTime}s\taverageTime   \t{$averageTime}s\n";
			echo "memoryUsage \t{$memory_usage}\taverageMemory \t{$averageMemory}";
			echo "\n---------------------------------------------------------\n";
		}
		$this->assertTrue(1 > $totalTime);
	}
	protected function setUp()
	{
		$_SERVER['SCRIPT_NAME'] = '/index.php';
		$_SERVER['PHP_SELF'] = '/index.php';
	}
	protected function tearDown()
	{
	}
}
