<?php
/**
 * 本测试文档演示了LtAutoloader的错误使用方法 
 * 不要按本文档描述的方式使用LtAutoloader
 */
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . "common.inc.php";
class WrongWayToUseAutoloader extends PHPUnit_Framework_TestCase
{
	/**
	 * 目录名带空格
	 * 
	 * 不支持这样做的原因：
	 * Windows和Unix对带空格的路径名（包括目录名和文件名）的转义是不一样的
	 * 目前没找到很好的方法解决这个问题
	 * 
	 * @expectedException PHPUnit_Framework_Error
	 */
	public function testDirNameWithSpace()
	{
		$autoloader = new LtAutoloader;
		$autoloader->autoloadPath = dirname(__FILE__) . "/test_data/dirname with space"; //这个目录确实存在
		$autoloader->init();
	}

	/**
	 * 目录不存在
	 * 
	 * 不支持这样做的原因：
	 * 出错了就应该给开发者提示，不能忽略过去
	 * 
	 * @expectedException PHPUnit_Framework_Error
	 */
	public function testDirNotExists()
	{
		$autoloader = new LtAutoloader;
		$autoloader->autoloadPath = dirname(__FILE__) . "/dir_not_exists"; //这个目录不存在
		$autoloader->init();
	}

	/**
	 * 类或接口重名
	 * 
	 * 不支持这样做的原因 
	 * 如果两个文件定义了同一个类
	 * 当需要自动加载的时候，autoloader不知道该载入哪个文件
	 * 接口在autoloader里面跟类完全相同
	 * 所以类和类不能重名，接口和接口不能重名，类和接口也不能重名
	 * 
	 * @expectedException PHPUnit_Framework_Error
	 */
	public function testDuplicateNameOfClasses()
	{
		$ap = new LtAutoloaderProxy;
		$ap->addClass("ClassA", __FILE__);
		$ap->addClass("classa", __FILE__);
	}

	/**
	 * 函数和函数重名 
	 * 
	 * 不支持这样做的原因 
	 * autoloader默认地会将定义了函数的文件自动包含进来 
	 * 因为函数无法按需加载 
	 * 如果两个文件定义了同一个函数 
	 * 当autoloader包含所有定义了函数的文件的时候，PHP引擎会报错
	 * 
	 * @expectedException PHPUnit_Framework_Error
	 */
	public function testDumplicateNameOfFunctions()
	{
		$ap = new LtAutoloaderProxy;
		$ap->addFunction("Function1", __FILE__);
		$ap->addFunction("function1", __FILE__);
	}

	protected function setUp()
	{
	}
	protected function tearDown()
	{
	}
}
