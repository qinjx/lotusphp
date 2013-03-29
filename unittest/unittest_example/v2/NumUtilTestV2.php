<?php
include "NumUtilV2.php";
include "../v1/NumUtilV1.php";

/**
 * 哎呀，上一版本的测试用例好像够多了，但是测试评审的时候大伙儿要疯掉的
 * 扁平化的结构不利于人脑处理，想象一下，2万人的公司，如果集团CEO要直管每一个人的话，连开个大会人齐了没有都管不过来
 * 所以，我们需要一棵树
 *
 * 让我们在白板上画个脑图
 */
class TestCaseNumUtilV2 extends PHPUnit_Framework_TestCase
{
	private $numUtil;
	public function setUp()
	{
        $this->numUtil = new NumUtilV1;
//		$this->numUtil = new NumUtilV2;
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
			array(200,array(0, -1, -2, 10, 5, 2)),//零的个数等于1 偶数个负数 有正数
			array(100,array(0, -1, -2, -10, -5)),//零的个数等于1 偶数个负数 无正数
			array(0,array(0, -1, 2, 3)),//零的个数等于1 奇数个负数 有正数
			array(0,array(0, -1, -2, -3)),//零的个数等于1 奇数个负数 无正数
			array(100,array( -1, -2, -10, -5, 10)),//零的个数小于1 偶数个负数 有正数
			array(-10,array( -1, -2, -1024, -5)),//零的个数小于1 偶数个负数 无正数
			array(200,array(-5, -10, -2, 4)),//零的个数小于1 奇数个负数 有正数
			array(50,array(-5, -10, -2)),//零的个数小于1 奇数个负数 无正数
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