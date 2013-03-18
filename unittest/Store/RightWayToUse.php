<?php
/**
 * 本测试文档演示了LtStore的正确使用方法 
 * 按本文档操作一定会得到正确的结果
 */
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . "common.inc.php";
class RightWayToUseStore extends PHPUnit_Framework_TestCase
{
    /**
     * LtStoreMemory
     *   非持久化存储：存在临时内存里，脚本结束，内存释放
     *   适用于：开发环境
     *
     * LtStoreFile
     *   持久化存储：序列化后存在硬盘文件上
     *   适用于：生产环境
     *
     * 由于LtStoreMemory和LtStoreFile都实现了LtStore接口，一行代码即可实现开发环境和生产环境的持久化存储切换
     *
     * LtStoreMemory的使用方法与LtStoreFile完全一样，只是LtStoreMemory没有任何属性可设置
     */
    public function testMostUsedWay()
	{
        /**
         * Lotus组件初始化三步曲
         */
        // 1. 实例化
		$storeHandle = new LtStoreFile;

        // 2. 设置属性
        $storeHandle->storeDir = "/tmp/" . uniqid();//不设置也有默认值的，这里只是演示一个三步曲

        // 3. 调用init()方法
        $storeHandle->init();

        //初始化完毕，测试其效果
        //存入一个键值对，返回true
		$this->assertTrue($storeHandle->add("test_key", "test_value"));

        //根据key取出之前存入的value
		$this->assertEquals("test_value", $storeHandle->get("test_key"));

        //更新存入的值
        $this->assertTrue($storeHandle->update("test_key", "new_value"));

        //再次取出，已经是更新之后的值了
        $this->assertEquals("new_value", $storeHandle->get("test_key"));

        //删除此key
		$this->assertTrue($storeHandle->del("test_key"));

        //再用此key来取出值，会失败，返回false
		$this->assertFalse($storeHandle->get("test_key")); 

		//删除、更新不存在的key
		$this->assertFalse($storeHandle->del("some_key_not_exists"));
		$this->assertFalse($storeHandle->update("some_key_not_exists", "any value")); 

		//添加重复的key
		$this->assertTrue($storeHandle->add("key1", "value1"));
		$this->assertFalse($storeHandle->add("key1", "value1"));
		$storeHandle->del("key1");
	}

    /**
     * 本用例展示了LtStore支持哪些Key类型
     * LtStore支持的Key类型与PHP的数组下标类型一样:
     *   key 可以是 integer 或者 string。如果key是一个 integer 的标准表示，则被解释为整数（例如 "8" 将被解释为 8，而 "08" 将被解释为 "08"）。key 中的浮点数被取整为 integer。
     * 来源：http://www.php.net/manual/zh/language.types.array.php
     *
     * 添加新的测试条请增加一行
     * array("Key示例", 是否支持)
     */
    public function testKeyTypeDataProvider()
    {
        return array(
            array(1024, true),//整数
            array(3.14, true),//浮点数
            array(007, true),//字符串，由数字组成
            array("1.414", true),//字符串
            array("test_string", true),//字符串，由字母组成
            array("string with white space", true),//带空格的字符串
        );
    }

	/**
	 * 测试数据类型支持情况
     * @dataProvider testKeyTypeDataProvider
	 */
	public function testKeyType($key, $excepted)
	{
        foreach(array("LtStoreMemory", "LtStoreFile") as $storeClass)
        {
            $storeHandle = new $storeClass;
            $storeHandle->init();
            $storeHandle->del($key);

            $value = uniqid();
            $result = $storeHandle->add($key, $value);

            $this->assertEquals($result, $excepted);

            if($result)
            {
                $this->assertEquals($storeHandle->get($key), $value);
            }
        }
	}

    /**
     * 本用例展示了LtStore支持哪些Value类型
     * LtStore支持的Value类型与PHP的serialize()一样:
     *   serialize 可以resource 之外的任何类型。
     * 来源：http://www.php.net/manual/zh/serialize
     *
     * 添加新的测试条请增加一行
     * array("Value示例", 是否支持)
     */
    public function testValueTypeDataProvider()
    {
        return array(
            array(TRUE, true),//布尔型
            array(1024, true),//整数
            array(3.14, true),//浮点数
            array("test_string", true),//字符串
            array(array(1,2,3), true),//数组
            array(array("a" => 1, "b" => 2), true),//字符串做下标的数组
            array(new LtStoreMemory, true),//对象
            array(NULL, true),//空
            array(xml_parser_create(), false),//资源类型，不支持
        );
    }

    /**
     * 测试数据类型支持情况
     * @dataProvider testKeyTypeDataProvider
     */
    public function testValueType($value, $excepted)
    {
        foreach(array("LtStoreMemory", "LtStoreFile") as $storeClass)
        {
            $storeHandle = new $storeClass;
            $storeHandle->init();

            $key = uniqid();
            $storeHandle->del($key);
            $result = $storeHandle->add($key, $value);

            $this->assertEquals($result, $excepted);

            if($result)
            {
                $this->assertEquals($storeHandle->get($key), $value);
            }
        }
    }

	protected function setUp()
	{
	}

	protected function tearDown()
	{
	}
}
