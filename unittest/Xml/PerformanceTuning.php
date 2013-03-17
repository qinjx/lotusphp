<?php
/**
 * url生成, 性能测试
 */
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . "common.inc.php";
class PerformanceTuningXml extends PHPUnit_Framework_TestCase
{
	public function testPerformance()
	{ 
		$originString = "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>\n<catalog>\n<book id=\"bk101\">\n<author>\nGambardella, Matthew\n</author>\n<title>\nXML Developer&apos;s Guide\n</title>\n<description>\nAn in-depth look at creating applications \nwith XML.\n</description>\n</book>\n<book id=\"bk102\">\n<author>\nRalls, Kim\n</author>\n<title>\nMidnight Rain\n</title>\n<description>\nA former architect battles corporate zombies, \nan evil sorceress, and her own childhood to become queen \nof the world.\n</description>\n</book>\n</catalog>\n";

		// 初始化LtXml
		$xml = new LtXml;
		$xml->init(); 
		// 初始化结束

		$xmlArr = $xml->getArray($originString);
		$this->assertEquals('catalog', $xmlArr["tag"]);
		$this->assertEquals(2, count($xmlArr["sub"]));

		$this->assertEquals('book', $xmlArr["sub"][0]["tag"]);
		$this->assertEquals(1, count($xmlArr["sub"][0]["attributes"]));
		$this->assertEquals('bk101', $xmlArr["sub"][0]["attributes"]["id"]);
		$this->assertEquals(3, count($xmlArr["sub"][0]["sub"]));
		$this->assertEquals('author', $xmlArr["sub"][0]["sub"][0]["tag"]);
		$this->assertEquals('Gambardella, Matthew', $xmlArr["sub"][0]["sub"][0]["cdata"]);

		$this->assertEquals('book', $xmlArr["sub"][1]["tag"]);
		$this->assertEquals(1, count($xmlArr["sub"][1]["attributes"]));
		$this->assertEquals('bk102', $xmlArr["sub"][1]["attributes"]["id"]);
		$this->assertEquals(3, count($xmlArr["sub"][1]["sub"]));
		$this->assertEquals('title', $xmlArr["sub"][1]["sub"][1]["tag"]);
		$this->assertEquals('Midnight Rain', $xmlArr["sub"][1]["sub"][1]["cdata"]);

		$xml->mode = WRITEMODE;
		$xmlString = $xml->getString($xmlArr);
		$this->assertEquals($originString, $xmlString);

		/**
		 * 运行100次，要求在1秒内运行完
		 */
		$base_memory_usage = memory_get_usage();
		$times = 100;
		$startTime = microtime(true);
		for($i = 0; $i < $times; $i++) {
			$xml->free();
			$xml->init();
			$xmlArr = $xml->getArray($originString);
			$xml->mode = WRITEMODE;
			$xmlString = $xml->getString($xmlArr);
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
	}
	protected function tearDown()
	{
	}
}
