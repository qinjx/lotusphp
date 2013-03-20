<?php
/**
 * 本测试文档演示了LtStore的错误使用方法
 * 不要按本文档描述的方式使用LtStore
 */
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . "common.inc.php";
class WrongWayToUseStore extends PHPUnit_Framework_TestCase
{
	/**
	 * 存储resource类型的Value是不支持的，特别是LtStoreFile
	 */
	public function testStoreResourceTypeValue()
	{
		$storeHandle = new LtStoreFile;
		$storeHandle->init();

		//创建一个resource类型的变量
		$res = xml_parser_create();
		$this->assertTrue(is_resource($res));

		//存到LtStoreFile里去
		$storeHandle->add("test_key", $res);

		//再取出来就变成int型了，只有resource id (一个整数）成功存入了
		$valueFromStore = $storeHandle->get("test_key");
		$this->assertTrue(is_int($valueFromStore));

		//显然他们不相等
		$this->assertNotEquals($res, $valueFromStore);
	}

	protected function setUp()
	{

	}

	protected function tearDown()
	{

	}
}
