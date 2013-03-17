<?php
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . "common.inc.php";
class PerformanceTuningLotus extends PHPUnit_Framework_TestCase
{
	public function testPerformance()
	{
		$option['proj_dir'] = dirname(__FILE__) . '/proj_dir/';
		$option['app_name'] = 'app_name2';

		/**
		 * 初始化Lotus类
		 */
		$lotus = new Lotus();
		$lotus->devMode = false;
		$lotus->defaultStoreDir='/tmp';
		$lotus->option = $option;
		$lotus->init();

		/**
		 * class_exists默认调用自动加载
		 */
		$this->asserttrue(class_exists("LtCaptcha"));

		/**
		 * 运行100次，要求在1秒内运行完
		 */
		$base_memory_usage = memory_get_usage();
		$times = 100;
		$startTime = microtime(true);

		for($i = 0; $i < $times; $i++)
		{
			$lotus = new Lotus();
			$lotus->devMode = false;
			$lotus->defaultStoreDir='/tmp';
			$lotus->option = $option;
			$lotus->init();
			unset($lotus);
		}

		$endTime = microtime(true);
		$totalTime = round(($endTime - $startTime), 6);
		$averageTime = round(($totalTime / $times), 6);

		$memory_usage = memory_get_usage() - $base_memory_usage;
		$averageMemory = formatSize($memory_usage / $times);
		$memory_usage = formatSize($memory_usage);
		if (LOTUS_UNITTEST_DEBUG)
		{
			echo "\n---------------------Lotus------------------------------\n";
			echo "times      \t$times\n";
			echo "totalTime   \t{$totalTime}s\taverageTime   \t{$averageTime}s\n";
			echo "memoryUsage \t{$memory_usage}\taverageMemory \t{$averageMemory}";
			echo "\n---------------------------------------------------------\n";
		}
		$this->assertTrue(1 > $totalTime);
	}

	protected function setUp()
	{
		$_SERVER['SERVER_PROTOCOL'] = 'HTTP/1.1';
		$_SERVER['PATH_INFO'] = '/Index/Index';
	}

	protected function tearDown()
	{
	}
}
