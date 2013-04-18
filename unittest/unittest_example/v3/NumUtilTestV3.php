<?php
include "../v2/NumUtilV2.php";
/**
 * 哎呀，画脑图毕竟不方便，一个开发维护一份脑图，一份测试代码，总有一次更新会忘了其中一个的
 * 那咱们用@see注释把它们融合到一个IDE中来吧
 *
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
 * 	零的个数大于1			@see TestCaseNumUtilV3::test_amountOfZeroGreaterThanOne()
 * 	零的个数等于1
 *		负数个数为偶数
 *			有正数		@see TestCaseNumUtilV3::test_amountOfZeroEqualsOne_amountOfNegativeIsEven_existsPositive()
 * 			无正数	   @see TestCaseNumUtilV3::test_amountOfZeroEqualsOne_amountOfNegativeIsEven_notExistsPositive()
 * 		负数个数为奇数
 * 			有正数		@see TestCaseNumUtilV3::test_amountOfZeroEqualsOne_amountOfNegativeIsOdd_existsPositive()
 *			无正数		@see TestCaseNumUtilV3::test_amountOfZeroEqualsOne_amountOfNegativeIsOdd_notExistsPositive()
 * 	零的个数小于1
 *		负数个数为偶数
 *			有正数		@see TestCaseNumUtilV3::test_amountOfZeroLessThanOne_amountOfNegativeIsEven_existsPositive()
 * 			无正数	   @see TestCaseNumUtilV3::test_amountOfZeroLessThanOne_amountOfNegativeIsEven_notExistsPositive()
 * 		负数个数为奇数
 * 			有正数		@see TestCaseNumUtilV3::test_amountOfZeroLessThanOne_amountOfNegativeIsOdd_existsPositive()
 *			无正数		@see TestCaseNumUtilV3::test_amountOfZeroLessThanOne_amountOfNegativeIsOdd_notExistsPositive()
 *
 *参数输入错误的异常流
 *	输入的参数不是数组		@see TestCaseNumUtilV3::test_inputIsNotArray()
 * #################### MECE Tree ####################
 */
class TestCaseNumUtilV3 extends PHPUnit_Framework_TestCase
{
	private $numUtil;
	public function setUp()
	{
		$this->numUtil = new NumUtilV2;
	}

	/**
	 * 零的个数大于1
	 * 本来根据根据负数个数奇偶性、正数有无可以分成四种情况
	 * 但这四种情况明显可以归并到这一种，因此不再分成四个条件来写
	 */
	public function test_amountOfZeroGreaterThanOne()
	{
		$this->assertEquals(0, $this->numUtil->findMaxProd(array(0, 0, 1, 2, 3, 4)));
	}

	/**
	 * 零的个数等于1 偶数个负数 有正数
	 */
	public function test_amountOfZeroEqualsOne_amountOfNegativeIsEven_existsPositive()
	{
		$this->assertEquals(200, $this->numUtil->findMaxProd(array(0, -1, -2, 10, 5, 2)));
	}

	/**
	 * 零的个数等于1 偶数个负数 无正数
	 */
	public function test_amountOfZeroEqualsOne_amountOfNegativeIsEven_notExistsPositive()
	{
		$this->assertEquals(100, $this->numUtil->findMaxProd(array(0, -1, -2, -10, -5)));
	}

	/**
	 * 零的个数等于1 奇数个负数 有正数
	 */
	public function test_amountOfZeroEqualsOne_amountOfNegativeIsOdd_existsPositive()
	{
		$this->assertEquals(0, $this->numUtil->findMaxProd(array(0, -1, 2, 3)));
	}

	/**
	 * 零的个数等于1 奇数个负数 无正数
	 */
	public function test_amountOfZeroEqualsOne_amountOfNegativeIsOdd_notExistsPositive()
	{
		$this->assertEquals(0, $this->numUtil->findMaxProd(array(0, -1, -2, -3)));
	}

	/**
	 * 零的个数小于1 偶数个负数 有正数
	 */
	public function test_amountOfZeroLessThanOne_amountOfNegativeIsEven_existsPositive()
	{
		$this->assertEquals(100, $this->numUtil->findMaxProd(array( -1, -2, -10, -5, 10)));
	}

	/**
	 * 零的个数小于1 偶数个负数 无正数
	 */
	public function test_amountOfZeroLessThanOne_amountOfNegativeIsEven_notExistsPositive()
	{
		$this->assertEquals(-10, $this->numUtil->findMaxProd(array( -1, -2, -1024, -5)));
	}

	/**
	 * 零的个数小于1 奇数个负数 有正数
	 */
	public function test_amountOfZeroLessThanOne_amountOfNegativeIsOdd_existsPositive()
	{
		$this->assertEquals(200, $this->numUtil->findMaxProd(array(-5, -10, -2, 4)));
	}

	/**
	 * 零的个数小于1 奇数个负数 无正数
	 */
	public function test_amountOfZeroLessThanOne_amountOfNegativeIsOdd_notExistsPositive()
	{
		$this->assertEquals(50, $this->numUtil->findMaxProd(array(-5, -10, -2)));
	}

	public function test_inputIsNotArrayDataProvider()
	{
		return array(
			array(NULL),
			array(TRUE),
			array(1024),
			array(3.14),
			array("not an array"),
			array(new TestCaseNumUtilV3),
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
}