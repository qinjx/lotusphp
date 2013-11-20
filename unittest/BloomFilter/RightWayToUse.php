<?php
/**
 * 本测试文档演示了LtBloomFilter的正确使用方法
 * 按本文档操作一定会得到正确的结果
 */
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . "common.inc.php";
class RightWayToUseBloomFilter extends PHPUnit_Framework_TestCase
{
	/**
	 * -------------------------------------------------------------------
	 * 本测试用例期望效果：
	 * 在new CLASS_NAME, class_exists("CLASS_NAME"), extends CLASS_NAME的时候 
	 * 自动把包含该类的文件加载进来
	 */
	public function testMostUsedWay()
	{
		/**
		 * Lotus组件初始化三步曲
		 */
		// 1. 实例化
		$bf = new LtBloomFilter;

		// 2. 设置属性
		$bf->setBucketSize(64 * 1024);
		$bf->setImageFile(sys_get_temp_dir() . "/bf-test-" . crc32(__FILE__) . ".bloom");

		// 3. 调init()方法
		$bf->init();

		//初始化完毕，测试其效果
		$bf->add("abcdefgh1234567890");
		$bf->add("本用例展示了LtAutoloader能识别哪些类和函数定义");
		$this->assertTrue($bf->has("abcdefgh1234567890"));
		$this->assertTrue($bf->has("本用例展示了LtAutoloader能识别哪些类和函数定义"));
		$this->assertFalse($bf->has("http://example.com/"));
	}

	/**
	 * 本用例展示了LtAutoloader能识别哪些类和函数定义 
	 * 
	 * 添加新的测试条请增加一个数组 
	 * array('定义类或函数的代码','正确结果')
	 */
	public function hashDataProvider()
	{
		return array(
			// 最常用的Class写法
			array("<?php
				class Src
				{
					public function parseSrc()//this function should not be parsed
					{
					}
				}",
				123
			),
			);
	}

	/**
	 * 测试hash()能否正确的识别源文件中定义的类,接口,函数
	 * 
	 * @dataProvider hashDataProvider
	 */
	public function testHash($src, $expected)
	{
		$ap = new LtBloomFilterProxy();
	}

	protected function setUp()
	{
	}
	protected function tearDown()
	{
	}
}
