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
	 * 测试NumUtilV7::statNumber方法
	 * 构造一个数组，由随机的正数、负数和零组成（他们的个数都可能为0）
	 * 上述测试重复100次
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
	 * 测试排除1个元素的功能
	 */
	public function kickOffOneNumber()
	{
		for($i = 0; $i < 100; $i++)
		{
			$arr = array(
				"min_p" => rand(1, 99),
				"max_n" => rand(-99, -1),
				"min_n" => rand(-999, -111),
			);

			//0的个数大于1
			$arr["az"] = rand(2, 100);
			$arr["an"] = rand(0, 100);
			$arr["ap"] = rand(0, 100);
			$this->assertEquals(NumUtilV7::MAX_PROD_IS_ZERO, $this->tp->kickOffOneNumber($arr));

			//0的个数等于1	奇数个负数	无正数
			$arr["az"] = 1;
			$arr["an"] = rand(0, 100) * 2 + 1;
			$arr["ap"] = 0;
			$this->assertEquals(NumUtilV7::MAX_PROD_IS_ZERO, $this->tp->kickOffOneNumber($arr));

			//0的个数等于1	奇数个负数	有正数
			$arr["az"] = 1;
			$arr["an"] = rand(0, 100) * 2 + 1;
			$arr["ap"] = rand(1, 100);
			$this->assertEquals(NumUtilV7::MAX_PROD_IS_ZERO, $this->tp->kickOffOneNumber($arr));

			//0的个数等于1	偶数个负数	无正数
			$arr["az"] = 1;
			$arr["an"] = rand(0, 100) * 2;
			$arr["ap"] = 0;
			$this->assertEquals(0, $this->tp->kickOffOneNumber($arr));

			//0的个数等于1	偶数个负数	有正数
			$arr["az"] = 1;
			$arr["an"] = rand(0, 100) * 2;
			$arr["ap"] = rand(1, 100);
			$this->assertEquals(0, $this->tp->kickOffOneNumber($arr));

			//0的个数小于1	奇数个负数	无正数
			$arr["az"] = 0;
			$arr["an"] = rand(0, 100) * 2 + 1;
			$arr["ap"] = 0;
			$this->assertEquals($arr["max_n"], $this->tp->kickOffOneNumber($arr));

			//0的个数小于1	奇数个负数	有正数
			$arr["az"] = 0;
			$arr["an"] = rand(0, 100) * 2 + 1;
			$arr["ap"] = rand(1, 100);
			$this->assertEquals($arr["max_n"], $this->tp->kickOffOneNumber($arr));

			//0的个数小于1	偶数个负数	无正数
			$arr["az"] = 0;
			$arr["an"] = rand(0, 100) * 2;
			$arr["ap"] = 0;
			$this->assertEquals($arr["min_n"], $this->tp->kickOffOneNumber($arr));

			//0的个数小于1	偶数个负数	有正数
			$arr["az"] = 0;
			$arr["an"] = rand(0, 100) * 2;
			$arr["ap"] = rand(1, 100);
			$this->assertEquals($arr["min_p"], $this->tp->kickOffOneNumber($arr));
		}
	}

	/**
	 * @test
	 * 测试NumUtilV7::calcProd()方法
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
			$this->assertEquals(
				$exp,
				$actual * $arr[$kickOffIndex]
			);


		}

	}
}