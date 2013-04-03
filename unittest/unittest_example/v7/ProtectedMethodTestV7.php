<?php
include "NumUtilV7.php";
include "TestProxyV7.php";

/**
 */
class ProtectedMethodTestV7 extends PHPUnit_Framework_TestCase
{
	private $tp;
	public function setUp()
	{
		$this->tp = new TestProxyV7();
	}

	/**
	 * @test
	 */
	public function checkInput()
	{

	}

	/**
	 * @test
	 */
	public function statNumber()
	{
		for($i = 0; $i < 100; $i++)
		{
			$amount_zero = 0;
			$amount_negative = 0;
			$amount_positive = 0;
			$min_positive = null;
			$min_negative = null;
			$max_negative = null;
			$arr = array();
			if (0 == rand(0, 12) % 2)
			{
				$zero_array = array_fill(0, rand(1, 10), 0);
				$amount_zero = count($zero_array);
				$arr = array_merge($arr, $zero_array);
			}
			if (0 == rand(0, 12) % 2)
			{
				$positive_array = array_fill(0, rand(1, 10), rand(111, 999));
				$amount_positive = count($positive_array);
				$min_positive = min($positive_array);
				$arr = array_merge($arr, $positive_array);
			}
			if (0 == rand(0, 12) % 2)
			{
				$negative_array = array_fill(0, rand(1, 10), rand(-999, -111));
				$min_negative = min($negative_array);
				$max_negative = max($negative_array);
				$amount_negative = count($negative_array);
				$arr = array_merge($arr, $negative_array);
			}
			$exp = array(
				"az" => $amount_zero,
				"an" => $amount_negative,
				"ap" => $amount_positive,
				"min_p" => $min_positive,
				"max_n" => $max_negative,
				"min_n" => $min_negative
			);
			$this->assertEquals(
				$exp,
				$this->tp->statNumber($arr)
			);
		}
	}

	/**
	 * @test
	 */
	public function kickOffOneNumber()
	{
		for($i = 0; $i < 10; $i++)
		{
			$arr = array(
				"min_p" => rand(1, 99),
				"max_n" => rand(-99, -1),
				"min_n" => rand(-999, -111),
			);


			$arr["az"] = rand(1, 100);
			$arr["an"] = rand(-99, -1);
			$arr["ap"] = rand(10, 20);
			$this->assertEquals(
				1,
				1
			);
		}
	}

	/**
	 * @test
	 * 构造一个随机数组，从中随机取出一个，交给calcProd()方法计算乘积，再乘以刚才随机取出的那个数，与array_product()的计算结果对比
	 * 上述测试重复100次
	 */
	public function calcProd()
	{
		for($i = 0; $i < 100; $i++)
		{
			$arraySize = rand(2, 10);
			for ($j = 0; $j < $arraySize; $j ++)
			{
				$arr[$j] = rand(-10, 10);
			}
			$kickOffIndex = rand(0, $arraySize-1);

			$exp = array_product($arr);
			$actual = $this->tp->calcProd($arr, $arr[$kickOffIndex]);
//			if ($exp != $actual * $arr[$kickOffIndex])
//			{
//				print_r($arr);
//				var_dump($this->tp->calcProd($arr, $arr[$kickOffIndex]));
//				var_dump($arr[$kickOffIndex]);
//			}
			$this->assertEquals(
				$exp,
				$actual * $arr[$kickOffIndex]
			);


		}

	}
}