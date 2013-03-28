<?php
include "NumUtil.php";
/**
 * 哎呀，画脑图毕竟不方便，一个开发维护一份脑图，一份测试代码，总有一次更新会忘了其中一个的
 * 那咱们用@see注释把它们融合到一个IDE中来吧
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