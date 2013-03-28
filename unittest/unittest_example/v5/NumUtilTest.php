<?php
include "NumUtil.php";
/**
 * 这次总譔可以了吧，不，有点不放心，要是测试用例里的数据稍稍换一换呢？
 *
 */
class TestCaseNumUtil extends PHPUnit_Framework_TestCase
{
	public function test1()
	{
		$this->assertEquals(120, $this->numUtil->findMaxProd(array(2,3,4,5,1)));
	}

	/**
	 * 零的个数等于1 偶数个负数 有正数
	 */
	public function test2()
	{
		$this->assertEquals(720, $this->numUtil->findMaxProd(array(6, 5, 4, 3, 2, 1,0)));
	}
}