<?php
include "NumUtilV1.php";
include "../v0/NumUtilV0.php";

/**
 * 哎呀，上一版没考虑0和负数，还有输入不合法的情况
 * 补点测试用例
 */
class TestCaseNumUtilV1 extends PHPUnit_Framework_TestCase
{
    private $numUtil;
    public function setUp()
    {
        $this->numUtil = new NumUtilV0;
//        $this->numUtil = new NumUtilV1;
    }

	/**
	 * 正常流
	 * @test
	 * @dataProvider  正常流测试数据素材()
	 */
	public function 正常流测试($excepted, $input_array)
	{
		 $this->assertEquals($excepted, $this->numUtil->findMaxProd($input_array));
	}

	public function 正常流测试数据素材()
	{
		return array(
			array(0, array(0, 0, 1, 2, 3, 4)),//零的个数大于1
			array(200, array(0, -1, -2, 10, 5, 2)),//零的个数等于1 偶数个负数
			array(0, array(0, -1, 2, 3)),//零的个数等于1 奇数个负数
			array(100, array( -1, -2, -10, -5, 10)),//零的个数小于1 偶数个负数
			array(200, array(-5, -10, -2, 4)),//零的个数小于1 奇数个负数
		);
	}

	/**
	 * 异常流
	 * @test
	 * @dataProvider  异常流测试数据素材()
	 * @expectedException PHPUnit_Framework_Error
	 */
	public function 异常流测试($input)
	{
		$this->numUtil->findMaxProd($input);
	}

	public function 异常流测试数据素材()
	{
		return array(
			array(NULL),//输入为空
			array(1024),//直接输入一个整数，不是数组
		);
	}
}