<?php
/**
 * 看起来完美之时，也是出现新隐患之日 --《代码之美》第七章《美丽测试》
 *
 * #################### MECE Tree ####################
 *参数输入正确的正常流程
 * 	零的个数大于1			@see TestCaseNumUtilV6::test_amountOfZeroGreaterThanOne()
 * 	零的个数等于1
 *		负数个数为偶数
 *			有正数		@see TestCaseNumUtilV6::test_amountOfZeroEqualsOne_amountOfNegativeIsEven_existsPositive()
 * 			无正数	   @see TestCaseNumUtilV6::test_amountOfZeroEqualsOne_amountOfNegativeIsEven_notExistsPositive()
 * 		负数个数为奇数
 * 			有正数		@see TestCaseNumUtilV6::test_amountOfZeroEqualsOne_amountOfNegativeIsOdd_existsPositive()
 *			无正数		@see TestCaseNumUtilV6::test_amountOfZeroEqualsOne_amountOfNegativeIsOdd_notExistsPositive()
 * 	零的个数小于1
 *		负数个数为偶数
 *			有正数		@see TestCaseNumUtilV6::test_amountOfZeroLessThanOne_amountOfNegativeIsEven_existsPositive()
 * 			无正数	   @see TestCaseNumUtilV6::test_amountOfZeroLessThanOne_amountOfNegativeIsEven_notExistsPositive()
 * 		负数个数为奇数
 * 			有正数		@see TestCaseNumUtilV6::test_amountOfZeroLessThanOne_amountOfNegativeIsOdd_existsPositive()
 *			无正数		@see TestCaseNumUtilV6::test_amountOfZeroLessThanOne_amountOfNegativeIsOdd_notExistsPositive()
 *
 *参数输入错误的异常流
 *	输入的参数不是数组		@see TestCaseNumUtilV6::test_inputIsNotArray()
 * 	是个数组
 * 		元素个数小于2个	@see TestCaseNumUtilV6::test_ArrayContainLessThanTwoInteger()
 *		元素大于等于2个
 * 			不全是整数	@see TestCaseNumUtilV6::test_ArrayContainNonInteger()
 *
 *白盒测试
 *	元素个数超过int型上限 @see TestCaseNumUtilV6::test_amountOfZeroGreaterThanMaxInt()
 *	元素的乘积超过PHP上限	@see TestCaseNumUtilV6::test_prodGreaterThanMaxInt()
 *
 * #################### MECE Tree ####################
 */
class TestCaseNumUtilV6 extends PHPUnit_Framework_TestCase
{
	private $numUtil;
	public function setUp()
	{
		$this->numUtil = new NumUtilV4;
//		$this->numUtil = new NumUtilV5;
	}

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
		$this->assertEquals(0, $this->numUtil->findMaxProd($arr));
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
		$this->assertEquals(0, $this->numUtil->findMaxProd($arr));
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
		$this->assertEquals(0, $this->numUtil->findMaxProd($arr));
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
			array(new TestCaseNumUtilV5),
		);
	}

	/**
	 * 输入的参数不是数组
	 * @dataProvider test_inputIsNotArrayDataProvider
	 * @expectedException PHPUnit_Framework_Error
	 */
	public function test_inputIsNotArray($arg)
	{
		$this->numUtil->findMaxProd($arg);
	}

	/**
	 * 数组元素个数小于2个
	 */
	public function test_ArrayContainLessThanTwoInteger()
	{
		$this->assertFalse($this->numUtil->findMaxProd(array(10)));
	}

	/**
	 * 数组元素不全是整数
	 */
	public function test_ArrayContainNonInteger()
	{
		$this->assertFalse($this->numUtil->findMaxProd(array(-2, TRUE, -5)));
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
		$this->assertEquals($exp * $randInt * $randInt, $this->numUtil->findMaxProd($arr));
	}
}