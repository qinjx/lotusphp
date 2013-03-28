<?php
include "NumUtil.php";
class TestCaseNumUtil extends PHPUnit_Framework_TestCase
{
	public function test1()
	{
		$this->assertEquals(120, NumUtil::findMaxProd(array(2,3,4,5,1)));
	}

	/**
	 * 零的个数等于1 偶数个负数 有正数
	 */
	public function test2()
	{
		$this->assertEquals(720, NumUtil::findMaxProd(array(6, 5, 4, 3, 2, 1,0)));
	}
}