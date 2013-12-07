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
 *      int数组索引和bit位置计算 @see RightWayToUseBloomFilter::testBitArraycalcKeyAndPos()
 *      位设置和读取 @see RightWayToUseBloomFilter::testBitArrayOp()
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
            $bucketSize = $this->randomBigNumber();
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
            $bucketSize = $this->randomBigNumber(1);
            $magicNumber = $bfp->magicNumbers[mt_rand(0, count($bfp->magicNumbers)-1)];
            $hash = $bfp->hash($str, $bucketSize, $magicNumber);
            $this->assertTrue(0 <= $hash);
            $this->assertTrue($hash <= $bucketSize);
        }
    }

    public function testBitArraySaveAndLoad1()
    {
        $file = sys_get_temp_dir() . "/bf-unittest-" . uniqid() . ".bloom";
        $bitArr = array(1);
        for ($i = 0; $i < 10; $i++) {
            $bitArr[mt_rand(0, 99)] = $this->randomBigNumber();
        }
        $bitArr[] = PHP_INT_MAX;
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
            $bitArr1[mt_rand(0, 99)] = $this->randomBigNumber();
            $bitArr2[mt_rand(0, 99)] = $this->randomBigNumber();
        }
        $bitArr1[] = 1;
        $bitArr2[] = PHP_INT_MAX;
        $bfp = new LtBloomFilterProxy();
        $bfp->setImageFile($file);
        $bfp->saveToDisk($bitArr1, $file);
        $bfp->saveToDisk($bitArr2, $file);

        $loaded = $bfp->loadFromDisk($file);
        unlink($file);
        $this->assertEquals($this->numArrayMerge($bitArr1, $bitArr2), $loaded);
    }

    public function testBitArrayOp1()
    {
        $bfp = new LtBloomFilterProxy();
        $arr = array();
        $bitPosArr = array(
            3,4,
            0,1,
            63,64,
            31,32,
            PHP_INT_MAX-1,
            PHP_INT_MAX
        );

        foreach($bitPosArr as $k) {
            $this->assertFalse($bfp->isBitSet($arr, $k));
            $bfp->bitSet($arr, $k);
            $this->assertTrue($bfp->isBitSet($arr, $k));
        }
    }

    public function testBitArrayOp()
    {
        $bfp = new LtBloomFilterProxy();


        for ($i = 0; $i < 100; $i++) {
            $bitPosArr[] = $this->randomBigNumber();
        }

        $bitPosArr = array(
            3,4,
            0,1,
            63,64,
            31,32,
            PHP_INT_MAX-1,
            PHP_INT_MAX
        );
        foreach($bitPosArr as $k) {
            $arr = array();
            $this->assertFalse($bfp->isBitSet($arr, $k));
            $bfp->bitSet($arr, $k);
            $this->assertTrue($bfp->isBitSet($arr, $k));
        }
    }

    public function testBitArrayCalcKeyAndPos()
    {
        $bfp = new LtBloomFilterProxy();
        $a = array(
            0 => array(0, 1),
            1 => array(0, 2),
            30 => array(0, 31),
        );
        if (8 === PHP_INT_SIZE) {//64-bit platform
            $a[31] = array(0, 32);
            $a[32] = array(0, 33);
            $a[62] = array(0, 63);
            $a[63] = array(1, 1);
            $a[64] = array(1, 2);
            $a[PHP_INT_MAX] = array(146402730743726600, 8);
        }

        foreach ($a as $k => $exp) {
            $this->assertEquals($exp, $bfp->calcKeyAndPos($k));
        }
    }

    protected function randomString($len = null)
    {
        $str = "";
        if (null === $len) {
            $len = mt_rand(1, 256);
        }

        for ($i = 0; $i < $len; $i++) {
            $str .= ord($this->randomBigNumber(0, 16));
        }

        return $str;
    }

    protected function randomBigNumber($minBits = 1, $maxBits = null) {
        if (null === $maxBits) {
            $maxBits = 8 * PHP_INT_SIZE - 2;
        }
        return mt_rand(1 << $minBits, 1 << mt_rand($minBits + 1, $maxBits));
    }

    protected function numArrayMerge() {
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
