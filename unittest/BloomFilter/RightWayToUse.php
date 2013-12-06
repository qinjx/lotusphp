<?php
/**
 * 本测试文档演示了LtBloomFilter的正确使用方法
 * 按本文档操作一定会得到正确的结果
 *
 * 接口测试
 *      各类setXXX方法
 *      add和has方法 @see RightWayToUseBloomFilter::testAddAndHas()
 * 内部实现测试
 *  Hash方法
 *      相同字串多次hash得到相同结果 @see RightWayToUseBloomFilter::testHash1()
 *      hash结果不越界 @see RightWayToUseBloomFilter::testHash2()
 *      hash冲突率恰当 @todo 暂时不测试这个
 *  BitArray位操作
 *  BitArray持久化
 *      新建文件并写入 @see RightWayToUseBloomFilter::testBitArraySaveAndLoad1()
 *      读取非空文件并修改和追加 @see RightWayToUseBloomFilter::testBitArraySaveAndLoad2()
 */
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . "common.inc.php";
class RightWayToUseBloomFilter extends PHPUnit_Framework_TestCase
{
	/**
	 * -------------------------------------------------------------------
	 * 本测试用例期望效果：
	 * 调用add()方法加入bit array后，调用has()方法能返回true
	 */
	public function testMostUsedWay()
	{
		/**
		 * Lotus组件初始化三步曲
		 */
		// 1. 实例化
		$bf = new LtBloomFilter;

		// 2. 设置属性
		$bf->setBucketSize(10000);
        $file = sys_get_temp_dir() . "/bf-unittest-" . crc32(__FILE__) . ".bloom";
        if (file_exists($file)) {
            unlink($file);
        }
		$bf->setImageFile($file);

		// 3. 调init()方法
		$bf->init();

		//初始化完毕，测试其效果
		$bf->add("abcdefgh1234567890");
		$bf->add("本用例展示了LtAutoloader能识别哪些类和函数定义");
		$this->assertTrue($bf->has("abcdefgh1234567890"));
		$this->assertTrue($bf->has("本用例展示了LtAutoloader能识别哪些类和函数定义"));
		$this->assertFalse($bf->has("http://example.com/"));

        //删除文件
        unlink($file);
	}

    public function testAddAndHas()
    {
        $bf = new LtBloomFilter;

        // 2. 设置属性
        $bf->setBucketSize(PHP_INT_MAX);
        $bf->setSyncThreshHold(PHP_INT_MAX);
        $file = sys_get_temp_dir() . "/bf-unittest-" . uniqid() . ".bloom";
        $bf->setImageFile($file);

        // 3. 调init()方法
        $bf->init();

        //初始化完毕，测试其效果
        for ($i = 0; $i < 500; $i++) {
            $str = $this->randomString(16);
            $this->assertFalse($bf->has($str));
            $bf->add($str);
            $this->assertTrue($bf->has($str));
        }

        unlink($file);
    }

    public function testHash1()
    {
        $bfp = new LtBloomFilterProxy();

        for ($i = 0; $i < 100; $i++) {
            $str = $this->randomString();
            $bucketSize = mt_rand(2, PHP_INT_MAX);
            $magicNumber = $bfp->magicNumbers[mt_rand(0, count($bfp->magicNumbers)-1)];
            $hash = $bfp->hash($str, $bucketSize, $magicNumber);
            $this->assertEquals($hash, $bfp->hash($str, $bucketSize, $magicNumber));
        }

    }

    public function testHash2()
    {
        $bfp = new LtBloomFilterProxy();

        for ($i = 0; $i < 100; $i++) {
            $str = $this->randomString();
            $bucketSize = mt_rand(2, PHP_INT_MAX);
            $magicNumber = $bfp->magicNumbers[mt_rand(0, count($bfp->magicNumbers)-1)];
            $hash = $bfp->hash($str, $bucketSize, $magicNumber);
            $this->assertTrue(0 <= $hash);
            $this->assertTrue($hash <= $bucketSize);
        }
    }

    public function testBitArraySaveAndLoad1()
    {
        $file = sys_get_temp_dir() . "/bf-unittest-" . uniqid() . ".bloom";
        $bitArr = array();
        for ($i = 0; $i < 10; $i++) {
            $bitArr[mt_rand(0, 99)] = mt_rand(0, PHP_INT_MAX);
        }
        $bfp = new LtBloomFilterProxy();
        $bfp->setImageFile($file);
        $bfp->saveToDisk($bitArr, $file);
        $loaded = $bfp->loadFromDisk($file);
        unlink($file);
        $this->assertEquals($bitArr, $loaded);
    }

    public function testBitArraySaveAndLoad2()
    {
        $file = sys_get_temp_dir() . "/bf-unittest-" . uniqid() . ".bloom";
        $bitArr1 = array();
        $bitArr2 = array();
        for ($i = 0; $i < 10; $i++) {
            $bitArr1[mt_rand(0, 99)] = mt_rand(0, 999);
            $bitArr2[mt_rand(0, 99)] = mt_rand(0, PHP_INT_MAX);
        }
        $bfp = new LtBloomFilterProxy();
        $bfp->setImageFile($file);
        $bfp->saveToDisk($bitArr1, $file);
        $bfp->saveToDisk($bitArr2, $file);

        $loaded = $bfp->loadFromDisk($file);
        unlink($file);
        $this->assertEquals($this->num_array_merge_num($bitArr1, $bitArr2), $loaded);
    }

    protected function randomString($len = null)
    {
        $str = "";
        if (null === $len) {
            $len = mt_rand(1, 512);
        }

        for ($i = 0; $i < $len; $i++) {
            $str .= ord(mt_rand(0, 256));
        }

        return $str;
    }

    protected function num_array_merge_num() {
        $args = func_get_args();
        $arr = $args[0];
        $i = 1;
        while (isset($args[$i])) {
            foreach ($args[$i] as $k => $v) {
                $arr[$k] = $v;
            }
            $i ++;
        }
        return $arr;
    }

	protected function setUp()
	{
	}
	protected function tearDown()
	{
	}
}
