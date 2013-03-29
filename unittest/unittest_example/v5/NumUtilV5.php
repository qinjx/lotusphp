<?php
class NumUtilV5
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