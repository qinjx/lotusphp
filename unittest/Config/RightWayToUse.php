<?php
/**
 * 本测试文档演示了LtConfig的正确使用方法 
 * 按本文档操作一定会得到正确的结果
 */
chdir(dirname(__FILE__));
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . "common.inc.php";
class RightWayToUseConfig extends PHPUnit_Framework_TestCase
{
	/**
	 * -------------------------------------------------------------------
	 * LtConfig要求： 
	 * # 配置文件以return array()形式返回一个配置数组 
	 * 
	 * -------------------------------------------------------------------
	 * LtConfig不在意： 
	 * # 配置文件中用什么变量名和常量名 
	 * 
	 * -------------------------------------------------------------------
	 * 本测试用例期望效果：
	 * 通过LtConfig类能取到定义在config_file里面的配置信息
	 */
	public function testMostUsedWay()
	{
		$conf = new LtConfig;
		$conf->init();

		$conf->loadConfigFile(dirname(__FILE__) . "/test_data/conf.php");

		$this->assertEquals("localhost", $conf->get("db.conn.host"));
		$this->assertEquals($conf->get("misc.test_array"), array("test_array_key_1" => "test_array_value_1",
				"test_array_key_2" => "test_array_value_2",
				));
		$this->assertEquals(time(), $conf->get("misc.now"));
	}

	protected function setUp()
	{
	}

	protected function tearDown()
	{
	}
}
