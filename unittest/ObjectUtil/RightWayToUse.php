<?php
/**
 * 本测试文档演示了LtObjectUtil的正确使用方法 
 * 按本文档操作一定会得到正确的结果
 */
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . "common.inc.php";
class RightWayToUseObjectUtil extends PHPUnit_Framework_TestCase
{
	/**
	 * 最常用的使用方式（推荐） 
	 * -------------------------------------------------------------------
	 * LtObjectUtil要求： 
	 *  # 通过singleton()管理单件
	 * 
	 * -------------------------------------------------------------------
	 * LtObjectUtil建议（不强求）：
	 *  # 使用singleton()方法时，参数的大小写 和类名大小写最好保持一致
	 * 
	 * 本测试用例期望效果：
	 * 实现单件模式：通过singleton()多次获取实例，实际使用的都是同一个实例
	 */
	public function testSingleton()
	{
		/**
		 * 这是唯一一个不遵守“Lotus组件初始化三步曲”规则的组件
		 * 因为它需要用个冒号静态调用
		 */
		$countInst = count(LtObjectUtil::$instances);
		$obj1 = LtObjectUtil::singleton("stdClass");//stdClass是php内置的类
		$obj2 = LtObjectUtil::singleton("stdClass");
		$countInst = count(LtObjectUtil::$instances) - $countInst;

		$this->assertTrue($obj1 === $obj2);
		$this->assertEquals(1, $countInst);
		
		$obj2->prop = 1;
		$this->assertEquals(1, $obj1->prop);
	}
	protected function setUp()
	{
	}
	protected function tearDown()
	{
	}
}
