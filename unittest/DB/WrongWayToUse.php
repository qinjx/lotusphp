<?php
/**
 * 本测试文档演示了LtDb的错误使用方法
 * 不要按本文档描述的方式使用LtDb
 */
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . "common.inc.php";
class WrongWayToUseDb extends PHPUnit_Framework_TestCase
{
	/**
	 * 1. 通过query()接口执行setSchema(), setCharset()语句
	 *    这会导致当前连接的schema, charset难以同步
	 */
	public function testWrongUsedWay()
	{

	}
	protected function setUp()
	{
	}
	protected function tearDown()
	{
	}
}