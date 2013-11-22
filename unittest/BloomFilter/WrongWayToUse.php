<?php
/**
 * 本测试文档演示了LtBloomFilter的错误使用方法 
 * 不要按本文档描述的方式使用LtBloomFilter
 *
 * 没有正常初始化
 *	没调init()方法（无论是否设置过属性）@see WrongWayToUseBloomFilter::testNotInvokeInit()
 * 	调用了init()方法
 * 		没设置任何一个属性 @see WrongWayToUseBloomFilter::testNotInvokeAnySetter()
 * 		设置了部分属性，部分必选的属性没设置 -- 只有一个必选属性，不存在这种情况
 * 		设置了全部必选属性
 * 			设置了错误的属性，调用了init()方法
 * 				BucketSize参数错误
 * 					数据类型错误 @see WrongWayToUseBloomFilter::testBadBucketSizeDataType
 * 					数值越界 @see WrongWayToUseBloomFilter::testBadBucketSizeValue
 * 				ErrorRate参数错误
 * 					数据类型错误 @see WrongWayToUseBloomFilter::testBadErrorRateDataType
 * 					数值越界 @see WrongWayToUseBloomFilter::testBadErrorRateDataType
 * 				SyncThreshHold参数错误 
 * 					数据类型错误 @see WrongWayToUseBloomFilter::testBadSyncThreshHoldDataType
 * 					数值越界 @see WrongWayToUseBloomFilter::testBadSyncThreshHoldDataType
 * 				ImageFile参数错误
 * 					ImageFile不是字符串类型 @see WrongWayToUseBloomFilter::testBadImageFileDataType
 * 					ImageFile不可读写 @see WrongWayToUseBloomFilter::testImageFilePermissionDenied
 * 					ImageFile不存在，且其上级目录不可写 @see WrongWayToUseBloomFilter::testImageFileNotExistsAndParentDirPermissionDenied
 * 			设置了正确的属性，调用了init()方法 @see RightWayToUseBloomFilter
 * 正常初始化了
 * 		传递给add()的参数不合法 @see WrongWayToUseBloomFilter::testBadAddDataType
 * 		传递给has()的参数不合法 @see WrongWayToUseBloomFilter::testBadHasDataType
 */
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . "common.inc.php";
class WrongWayToUseBloomFilter extends PHPUnit_Framework_TestCase
{
	/**
	 * 测试setBucketSize()方法不支持的参数数据类型
	 * @return array
	 */
	public function dpBadBucketSizeDataType() {
		return array(
			array(NULL),
			array(TRUE),
			array(3.14),
			array("not an array"),
			array(new WrongWayToUseBloomFilter),
		);
	}

	/**
	 * 测试setBucketSize()方法不支持的参数值
	 * @return array
	 */
	public function dpBadBucketSizeValue() {
		return array(
			array(0),
			array(-1),
			array(PHP_INT_MAX * 2),
		);
	}

	/**
	 * 测试setErrorRate()方法不支持的参数数据类型
	 * @return array
	 */
	public function dpBadErrorRateDataType() {
		return array(
			array(NULL),
			array(TRUE),
			array(1024),
			array("not an array"),
			array(new WrongWayToUseBloomFilter),
		);
	}

	/**
	 * 测试setErrorRate()方法不支持的参数值
	 * @return array
	 */
	public function dpBadErrorRateValue() {
		return array(
			array(0),
			array(1),
			array(2),
			array(1E-10),
			array(PHP_INT_MAX * 2),
		);
	}

	/**
	 * 测试setSyncThreshHold()方法不支持的参数数据类型
	 * @return array
	 */
	public function dpBadSyncThreshHoldDataType() {
		return array(
			array(NULL),
			array(TRUE),
			array(3.14),
			array("not an array"),
			array(new WrongWayToUseBloomFilter),
		);
	}

	/**
	 * 测试setSyncThreshHold()方法不支持的参数值
	 * @return array
	 */
	public function dpBadSyncThreshHoldValue() {
		return array(
			array(0),
			array(-1),
			array(PHP_INT_MAX * 2),
		);
	}

	/**
	 * 测试setImageFile()方法不支持的参数数据类型
	 * @return array
	 */
	public function dpBadImageFileDataType() {
		return array(
			array(NULL),
			array(TRUE),
			array(777),
			array(3.14),
			array(new WrongWayToUseBloomFilter),
		);
	}

	/**
	 * 测试setImageFile()方法不支持的参数值
	 * @return array
	 */
	public function dpBadImageFileValue() {
		return array(
			array(0),
			array(-1),
			array(PHP_INT_MAX * 2),
		);
	}

	/**
	 * 测试setImageFile() - 没有写权限的文件
	 * @return array
	 */
	public function dpImageFilePermissionDenied() {
		return array(
			array("/etc/ssh_config"),
		);
	}

	/**
	 * 测试setImageFile() - 文件不存在，且其上级目录不可写
	 * @return array
	 */
	public function dpImageFileNotExistsAndParentDirPermissionDenied() {
		return array(
			array("/etc/pam.d/not_exists.file"),
		);
	}

	/**
	 * 测试add()方法不支持的参数数据类型
	 * @return array
	 */
	public function dpBadAddDataType() {
		return array(
			array(NULL),
			array(TRUE),
			array(777),
			array(3.14),
			array(new WrongWayToUseBloomFilter),
		);
	}

	/**
	 * 测试has()方法不支持的参数数据类型
	 * @return array
	 */
	public function dpBadHasDataType() {
		return array(
			array(NULL),
			array(TRUE),
			array(777),
			array(3.14),
			array(new WrongWayToUseBloomFilter),
		);
	}

	/**
	 * @expectedException PHPUnit_Framework_Error
	 */
	public function testNotInvokeInit()
	{
		$bf = new LtBloomFilter;
		$bf->add("aaa");
	}

	/**
	 * @expectedException PHPUnit_Framework_Error
	 */
	public function testNotInvokeAnySetter()
	{
		$bf = new LtBloomFilter;
		$bf->init();
		$bf->add("aaa");
	}

	/**
	 * @expectedException PHPUnit_Framework_Error
	 * @dataProvider dpBadBucketSizeDataType
	 */
	public function testBadBucketSizeDataType($para)
	{
		$bf = new LtBloomFilter;
		$bf->setBucketSize($para);
	}

	/**
	 *  PHPUnit_Framework_Error
	 * @expectedException PHPUnit_Framework_Error
	 * @dataProvider dpBadBucketSizeValue
	 */
	public function testBadBucketSizeValue($para)
	{
		$bf = new LtBloomFilter;
		$bf->setBucketSize($para);
	}

	/**
	 * @expectedException PHPUnit_Framework_Error
	 * @dataProvider dpBadErrorRateDataType
	 */
	public function testBadErrorRateDataType($para)
	{
		$bf = new LtBloomFilter;
		$bf->setErrorRate($para);
	}

	/**
	 *  PHPUnit_Framework_Error
	 * @expectedException PHPUnit_Framework_Error
	 * @dataProvider dpBadErrorRateValue
	 */
	public function testBadErrorRateValue($para)
	{
		$bf = new LtBloomFilter;
		$bf->setErrorRate($para);
	}

	/**
	 * @expectedException PHPUnit_Framework_Error
	 * @dataProvider dpBadSyncThreshHoldDataType
	 */
	public function testBadSyncThreshHoldDataType($para)
	{
		$bf = new LtBloomFilter;
		$bf->setSyncThreshHold($para);
	}

	/**
	 *  PHPUnit_Framework_Error
	 * @expectedException PHPUnit_Framework_Error
	 * @dataProvider dpBadSyncThreshHoldValue
	 */
	public function testBadSyncThreshHoldValue($para)
	{
		$bf = new LtBloomFilter;
		$bf->setSyncThreshHold($para);
	}

	/**
	 * @expectedException PHPUnit_Framework_Error
	 * @dataProvider dpBadImageFileDataType
	 */
	public function testBadImageFileDataType($para)
	{
		$bf = new LtBloomFilter;
		$bf->setImageFile($para);
	}

	/**
	 * @expectedException PHPUnit_Framework_Error
	 * @dataProvider dpImageFilePermissionDenied
	 */
	public function testImageFilePermissionDenied($para)
	{
		$bf = new LtBloomFilter;
		$bf->setImageFile($para);
	}

	/**
	 * @expectedException PHPUnit_Framework_Error
	 * @dataProvider dpImageFileNotExistsAndParentDirPermissionDenied
	 */
	public function testImageFileNotExistsAndParentDirPermissionDenied($para)
	{
		$bf = new LtBloomFilter;
		$bf->setImageFile($para);
	}

	/**
	 * @dataProvider dpBadAddDataType
	 */
	public function testBadAddDataType($para)
	{
		$bf = new LtBloomFilter;
		$bf->setBucketSize(1024);
		$bf->init();
		$this->assertFalse($bf->add($para));
	}

	/**
	 * @dataProvider dpBadHasDataType
	 */
	public function testBadHasDataType($para)
	{
		$bf = new LtBloomFilter;
		$bf->setBucketSize(1024);
		$bf->init();
		$this->assertFalse($bf->has($para));
	}

	protected function setUp()
	{
	}
	protected function tearDown()
	{
	}
}
