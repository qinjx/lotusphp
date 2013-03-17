<?php
/**
 * 本测试文档演示了LtConfig的错误使用方法
 * 不要按本文档描述的方式使用LtConfig
 */
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . "common.inc.php";
class WrongWayToUseConfig extends PHPUnit_Framework_TestCase
{
	/**
	 * config file中没有return array
	 * 
	 * @expectedException PHPUnit_Framework_Error
	 */
	public function testNotReturnArray()
	{
		$conf = new LtConfig;
		$conf->init();

		$conf->loadConfigFile(dirname(__FILE__) . "/test_data/conf_err.php");
	}
	/**
	 * config file不存在
	 * 
	 * @expectedException PHPUnit_Framework_Error
	 */
	public function testNotExistsConfigFile()
	{
		$conf = new LtConfig;
		// $conf->configFile = dirname(__FILE__) . "/test_data/conf_not_exists.php";
		$conf->init();

		$conf->loadConfigFile(dirname(__FILE__) . "/test_data/conf_not_exists.php");
	}

	protected function setUp()
	{
	}

	protected function tearDown()
	{
	}
}
