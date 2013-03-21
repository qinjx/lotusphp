<?php
/**
 * 待测试对象：一个计算整数数组最大乘积的类
 * 设有一整数数组，元素个数为N，求其中N-1个元素相乘的最大乘积。
 * 例如：输入数组[2,4,5,3]；则返回60，（对应的N-1个元素是[3,4,5]，它比[2,3,4],[2,3,5],[2,4,5]三种组合的乘积都大）。
 */
class NumUtil
{
	/**
	 * @param array $arr
	 * @return integer
	 */
	static public function findMaxProd(array $arr)
	{

		$arr_len = count($arr);
		if (2 > $arr_len)
		{
			return false;
		}

		/*
		 * 先遍历数组找出零、负数、正数的数量
		 * 只做统计，不排序，不做乘法
		 */
		$amount_zero = 0;//零的个数
		$amount_negative = 0;//负数个数
		$amount_positive = 0;//正数个数
		$min_positive_index = null;
		$min_negative_index = null;
		$max_negative_index = null;
		$the_only_zero_index = null;

		for($i = 0; $i < $arr_len; $i++)
		{
			if (!is_int($arr[$i]))
			{
				return false;
			}
			if (0 > $arr[$i])
			{
				$amount_negative += 1;
				if (is_null($min_negative_index) || $arr[$i] < $arr[$min_negative_index])
				{
					$min_negative_index = $i;
				}
				if (is_null($max_negative_index) || $arr[$i] > $arr[$max_negative_index])
				{
					$max_negative_index = $i;
				}
			}
			else if (0 == $arr[$i])
			{
				$amount_zero += 1;
				$the_only_zero_index = $i;
			}
			else
			{
				$amount_positive += 1;
				if (is_null($min_positive_index) || $arr[$i] < $arr[$min_positive_index])
				{
					$min_positive_index = $i;
				}
			}
		}

		/**
		 * Logical control start
		 */
		if (1 < $amount_zero)
		{
			/*
			 * 0的个数大于1，任意取N-1个元素，其乘积都是0
			 * 故无须再判断正数和负数的个数
			 */
			return 0;
		}
		else if (1 == $amount_zero)
		{
			if (1 == $amount_negative % 2)
			{//奇数个负数
				/*
				 * 最大乘积只能是0，无需判断正数个数
				 */
				return 0;
			} else {//偶数个负数
				/*
				 * 除0之外的N-1个整数乘积最大
				 */
				$pick_out_index = $the_only_zero_index;
			}
		}
		else// if (1 > $amount_zero)
		{
			if (1 == $amount_negative % 2)//奇数个负数
			{
				/*
				 * 除【绝对值最小的负数】之外的N-1个整数乘积最大
				 */
				$pick_out_index = $max_negative_index;
			}
			else//偶数个负数
			{
				if (0 < $amount_positive)
				{//存在正数
					/*
					 * 除【绝对值最小的正数】之外的N-1个整数乘积最大
					 */
					$pick_out_index = $min_positive_index;
				}
				else
				{
					/*
					 * 除【绝对值最大的负数】之外的N-1个整数乘积最大
					 * 乘积为负
					 */
					$pick_out_index = $min_negative_index;
				}
			}
		}

		/**
		 * 若需要计算N-1个元素的乘积
		 */
		$prod = 1;
		for($i = 0; $i < $arr_len; $i++)
		{
			if ($i != $pick_out_index)
			{
				$prod *= $arr[$i];
			}
		}
		return $prod;
	}
}

/**
 * 函数名释义：
 * az: Amount of Zero, 零的个数
 * ap: Amount of Positive, 正整数个数
 * an: Amount of Negative, 负数个数
 *
 * gt: Greater Than, 大于
 * eq: Equal, 等于
 * lt: Less Than, 小于
 *
 * o: odd, 奇数
 * e: even, 偶数
 *
 * #################### MECE Tree ####################
 *参数输入正确的正常流程
 * 	零的个数大于1			@see TestCaseNumUtil::test_amountOfZeroGreaterThanOne()
 * 	零的个数等于1
 *		负数个数为偶数
 *			有正数		@see TestCaseNumUtil::test_amountOfZeroEqualsOne_amountOfNegativeIsEven_existsPositive()
 * 			无正数	   @see TestCaseNumUtil::test_amountOfZeroEqualsOne_amountOfNegativeIsEven_notExistsPositive()
 * 		负数个数为奇数
 * 			有正数		@see TestCaseNumUtil::test_amountOfZeroEqualsOne_amountOfNegativeIsOdd_existsPositive()
 *			无正数		@see TestCaseNumUtil::test_amountOfZeroEqualsOne_amountOfNegativeIsOdd_notExistsPositive()
 * 	零的个数小于1
 *		负数个数为偶数
 *			有正数		@see TestCaseNumUtil::test_amountOfZeroLessThanOne_amountOfNegativeIsEven_existsPositive()
 * 			无正数	   @see TestCaseNumUtil::test_amountOfZeroLessThanOne_amountOfNegativeIsEven_notExistsPositive()
 * 		负数个数为奇数
 * 			有正数		@see TestCaseNumUtil::test_amountOfZeroLessThanOne_amountOfNegativeIsOdd_existsPositive()
 *			无正数		@see TestCaseNumUtil::test_amountOfZeroLessThanOne_amountOfNegativeIsOdd_notExistsPositive()
 *
 *参数输入错误的异常流
 *	输入的参数不是数组		@see TestCaseNumUtil::test_inputIsNotArray()
 * 	是个数组
 * 		元素个数小于2个	@see TestCaseNumUtil::test_ArrayContainLessThanTwoInteger()
 *		元素多于2个
 * 			不全是整数	@see TestCaseNumUtil::test_ArrayContainNonInteger()
 *
 *白盒测试
 *	元素个数超过int型上限 @see TestCaseNumUtil::test_amountOfZeroGreaterThanMaxInt()
 *	元素的乘积超过PHP上限	@see TestCaseNumUtil::test_prodGreaterThanMaxInt()
 *
 * #################### MECE Tree ####################
 */
class TestCaseNumUtil extends PHPUnit_Framework_TestCase
{
	/**
	 * 零的个数大于1
	 * 本来根据根据负数个数奇偶性、正数有无可以分成四种情况
	 * 但这四种情况明显可以归并到这一种，因此不再分成四个条件来写
	 */
	public function test_amountOfZeroGreaterThanOne()
	{
		$arr = array_merge(
			$this->produceIntArray(rand(2, 10), self::INT_SIGN_ZERO),
			$this->produceIntArray(rand(10, 20), self::INT_SIGN_RAND)
		);
		$this->assertEquals(0, NumUtil::findMaxProd($arr));
	}

	/**
	 * 零的个数等于1 偶数个负数 有正数
	 */
	public function test_amountOfZeroEqualsOne_amountOfNegativeIsEven_existsPositive()
	{
		$this->execTest(200, array(0, -1, -2, 10, 5, 2));
	}

	/**
	 * 零的个数等于1 偶数个负数 无正数
	 */
	public function test_amountOfZeroEqualsOne_amountOfNegativeIsEven_notExistsPositive()
	{
		$this->execTest(100, array(0, -1, -2, -10, -5));
	}

	/**
	 * 零的个数等于1 奇数个负数 有正数
	 */
	public function test_amountOfZeroEqualsOne_amountOfNegativeIsOdd_existsPositive()
	{
		$arr = array_merge(
			$this->produceIntArray(11, self::INT_SIGN_NEGA),
			array(0),
			$this->produceIntArray(rand(1,10), self::INT_SIGN_POSI)
		);
		$this->assertEquals(0, NumUtil::findMaxProd($arr));
	}

	/**
	 * 零的个数等于1 奇数个负数 无正数
	 */
	public function test_amountOfZeroEqualsOne_amountOfNegativeIsOdd_notExistsPositive()
	{
		$arr = array_merge(
			$this->produceIntArray(11, self::INT_SIGN_NEGA),
			array(0)
		);
		$this->assertEquals(0, NumUtil::findMaxProd($arr));
	}

	/**
	 * 零的个数小于1 偶数个负数 有正数
	 */
	public function test_amountOfZeroLessThanOne_amountOfNegativeIsEven_existsPositive()
	{
		$this->execTest(100, array( -1, -2, -10, -5, 10));
	}

	/**
	 * 零的个数小于1 偶数个负数 无正数
	 */
	public function test_amountOfZeroLessThanOne_amountOfNegativeIsEven_notExistsPositive()
	{
		$this->execTest(-10, array( -1, -2, -1024, -5));
	}

	/**
	 * 零的个数小于1 奇数个负数 有正数
	 */
	public function test_amountOfZeroLessThanOne_amountOfNegativeIsOdd_existsPositive()
	{
		$this->execTest(200, array(-2, -10, -5, 4), self::INT_SIGN_POSI);
	}

	/**
	 * 零的个数小于1 奇数个负数 无正数
	 */
	public function test_amountOfZeroLessThanOne_amountOfNegativeIsOdd_notExistsPositive()
	{
		$this->execTest(50, array(-2, -10, -5), self::INT_SIGN_POSI);
	}

	public function test_inputIsNotArrayDataProvider()
	{
		return array(
			array(NULL),
			array(TRUE),
			array(1024),
			array(3.14),
			array("not an array"),
			array(new TestCaseNumUtil),
		);
	}

	/**
	 * 输入的参数不是数组
	 * @dataProvider test_inputIsNotArrayDataProvider
	 * @expectedException PHPUnit_Framework_Error
	 */
	public function test_inputIsNotArray($arg)
	{
		NumUtil::findMaxProd($arg);
	}

	/**
	 * 数组元素个数小于2个
	 */
	public function test_ArrayContainLessThanTwoInteger()
	{
		$this->assertFalse(NumUtil::findMaxProd(array(10)));
	}

	/**
	 * 数组元素不全是整数
	 */
	public function test_ArrayContainNonInteger()
	{
		$this->assertFalse(NumUtil::findMaxProd(array(-2, TRUE, -5)));
	}

	/**
	 * 如果代码中用整形来记录【零、正数、负数】的个数，输入的数组元素个数超过int型上限，就会造成数据溢出
	 */
	public function test_amountOfZeroGreaterThanMaxInt()
	{
		//这种极端情况不支持，也不测试，写在这里仅仅表示我考虑到这点了
	}

	/**
	 * N-1个元素的乘积超过PHP能表达的上限，就会造成数据溢出
	 */
	public function test_prodGreaterThanMaxInt()
	{
		//这种情况暂时不支持，也不测试，写在这里仅仅表示我考虑到这点了
	}

	const INT_SIGN_POSI = "positive";
	const INT_SIGN_NEGA = "negative";
	const INT_SIGN_ZERO = "zero";
	const INT_SIGN_RAND = "RAND";

	private function  produceIntArray($length, $sign)
	{
		$int_arr = array();
		switch($sign)
		{
			case self::INT_SIGN_POSI :
				for($i = 0; $i < $length; $i++)
				{
					$int_arr[$i] = rand(1, 99);
				}
				break;
			case self::INT_SIGN_NEGA :
				for($i = 0; $i < $length; $i++)
				{
					$int_arr[$i] = 0 - rand(1, 99);
				}
				break;
			case self::INT_SIGN_ZERO :
				for($i = 0; $i < $length; $i++)
				{
					$int_arr[$i] = 0;
				}
				break;
			case self::INT_SIGN_RAND :
				for($i = 0; $i < $length; $i++)
				{
					$int_arr[$i] = $i % 2 ? rand(1, 99) : 0 - rand(0, 99);
				}
				break;
		}
		return $int_arr;
	}

	private function execTest($exp, array $arr, $sign = self::INT_SIGN_NEGA)
	{
		$randInt = self::INT_SIGN_POSI == $sign ? rand(1, 100) : 0 - rand(1, 99);
		$arr[] = $randInt;
		$arr[] = $randInt;
		shuffle($arr);
		$this->assertEquals($exp * $randInt * $randInt, NumUtil::findMaxProd($arr));
	}
}