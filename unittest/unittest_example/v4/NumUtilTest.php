<?php
include "NumUtil.php";
/**
 * 再来看看整合后的测试用例MECE树，真的MECE了吗？
 * 艾玛，好像没考虑入参不合法的情况
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