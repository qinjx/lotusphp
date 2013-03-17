<?php
/**
 * 本测试文档演示了LtObjectUtil的错误使用方法 
 * 不要按本文档描述的方式使用LtObjectUtil
 */
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . "common.inc.php";
class WrongWayToUseObjectUtil extends PHPUnit_Framework_TestCase
{
	/**
	 * 调用singleton()传入空值
	 * 
	 * @expectedException PHPUnit_Framework_Error
	 */
	public function testBlankClass()
	{
		LtObjectUtil::singleton("");
	}
	protected function setUp()
	{
	}
	protected function tearDown()
	{
	}
}
