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
	public function findMaxProd(array $arr)
	{

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
 * lt: Lesser Than, 小于
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
 * 			无正数       @see TestCaseNumUtil::test_amountOfZeroEqualsOne_amountOfNegativeIsEven_notExistsPositive()
 * 		负数个数为奇数
 * 			有正数		@see TestCaseNumUtil::test_amountOfZeroEqualsOne_amountOfNegativeIsOdd_existsPositive()
 *			无正数		@see TestCaseNumUtil::test_amountOfZeroEqualsOne_amountOfNegativeIsOdd_notExistsPositive()
 * 	零的个数小于1
 *		负数个数为偶数
 *			有正数		@see TestCaseNumUtil::test_amountOfZeroLesserThanOne_amountOfNegativeIsEven_existsPositive()
 * 			无正数       @see TestCaseNumUtil::test_amountOfZeroLesserThanOne_amountOfNegativeIsEven_notExistsPositive()
 * 		负数个数为奇数
 * 			有正数		@see TestCaseNumUtil::test_amountOfZeroLesserThanOne_amountOfNegativeIsOdd_existsPositive()
 *			无正数		@see TestCaseNumUtil::test_amountOfZeroLesserThanOne_amountOfNegativeIsOdd_notExistsPositive()
 *
 *参数输入错误的异常流
 *	输入的参数不是数组		@see TestCaseNumUtil::test_inputIsNotArray()
 * 	数组元素个数小于2个	@see TestCaseNumUtil::test_ArrayContainLesserThanTwoInteger()
 *
 *白盒测试
 *	元素个数超过int型上限 @see TestCaseNumUtil::test_amountOfZeroGreaterThanMaxInt()
 *
 * #################### MECE Tree ####################
 */
class TestCaseNumUtil
{
	/**
	 * 零的个数大于1
	 * 本来根据根据负数个数奇偶性、正数有无可以分成四种情况
	 * 但这四种情况明显可以归并到这一种，因此不再分成四个条件来写
	 */
	public function test_amountOfZeroGreaterThanOne()
	{
	}

	/**
	 * 零的个数等于1 偶数个负数 有正数
	 */
	public function test_amountOfZeroEqualsOne_amountOfNegativeIsEven_existsPositive()
	{
	}

	/**
	 * 零的个数等于1 偶数个负数 无正数
	 */
	public function test_amountOfZeroEqualsOne_amountOfNegativeIsEven_notExistsPositive()
	{
	}

	/**
	 * 零的个数等于1 奇数个负数 有正数
	 */
	public function test_amountOfZeroEqualsOne_amountOfNegativeIsOdd_existsPositive()
	{
	}

	/**
	 * 零的个数等于1 奇数个负数 无正数
	 */
	public function test_amountOfZeroEqualsOne_amountOfNegativeIsOdd_notExistsPositive()
	{
	}

	/**
	 * 零的个数小于1 偶数个负数 有正数
	 */
	public function test_amountOfZeroLesserThanOne_amountOfNegativeIsEven_existsPositive()
	{
	}

	/**
	 * 零的个数小于1 偶数个负数 无正数
	 */
	public function test_amountOfZeroLesserThanOne_amountOfNegativeIsEven_notExistsPositive()
	{
	}

	/**
	 * 零的个数小于1 奇数个负数 有正数
	 */
	public function test_amountOfZeroLesserThanOne_amountOfNegativeIsOdd_existsPositive()
	{
	}

	/**
	 * 零的个数小于1 奇数个负数 无正数
	 */
	public function test_amountOfZeroLesserThanOne_amountOfNegativeIsOdd_notExistsPositive()
	{
	}

	/**
	 * 输入的参数不是数组
	 */
	public function test_inputIsNotArray()
	{
		//这种极端情况不支持，也不测试，写在这里仅仅表示我考虑到这点了
	}

	/**
	 * 数组元素个数小于2个
	 */
	public function test_ArrayContainLesserThanTwoInteger()
	{
		//这种极端情况不支持，也不测试，写在这里仅仅表示我考虑到这点了
	}

	/**
	 * 如果代码中用整形来记录【零、正数、负数】的个数，输入的数组元素个数超过int型上限，就会造成数据溢出
	 */
	public function test_amountOfZeroGreaterThanMaxInt()
	{
		//这种极端情况不支持，也不测试，写在这里仅仅表示我考虑到这点了
	}
}