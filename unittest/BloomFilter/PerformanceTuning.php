<?php
/**
 * 本测试用于验证一些性能数据
 */
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . "common.inc.php";

class PerformanceTuningBloomFilter extends PHPUnit_Framework_TestCase
{
	/**
	 * 测试CPU消耗
	 */
	public function testPerformance()
	{
		/**
		 * 运行500次，要求在1秒内运行完
		 */
		$times = 500;
		$file = sys_get_temp_dir() . DIRECTORY_SEPARATOR . uniqid();
		$bf = new LtBloomFilter;
		$bf->setBucketSize(PHP_INT_MAX);
		$bf->setSyncThreshHold($times + 1);
		$bf->setImageFile($file);
		$bf->init();
		$base_memory_usage = memory_get_usage();
		$startTime = microtime(true);
		for($i = 0; $i < $times; $i++)
		{
			$str = uniqid();
			$bf->add($str);
			$bf->has($str);
		}
		$endTime = microtime(true);
		$memory_usage = memory_get_usage() - $base_memory_usage;
		unlink($file);
		$totalTime = round(($endTime - $startTime), 6);
		$averageTime = round(($totalTime / $times), 6);


		$averageMemory = formatSize($memory_usage / $times);
		$memory_usage = formatSize($memory_usage);
		if (LOTUS_UNITTEST_DEBUG)
		{
			echo "\n---------------------Bloom Filter--------------------------\n";
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
