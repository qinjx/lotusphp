<?php
/**
 * Class NumUtilV8
 * @todo 白盒测试
 * 1. 不需要记录正/负/零的确切数量，只要记录正数的有无、负数个数的奇偶、零是否大于/等于/小于1，可以防止统计计数时整数溢出
 * 2. 使用while代替for (;$i < $arrlen; )防止数组遍历时整数溢出
 *
 */
class NumUtilV8
{
	const MAX_PROD_IS_ZERO = "kick any number";
	/**
	 * @param array $arr
	 * @return int|bool
	 */
	public function findMaxProd(array $arr)
	{

		if (false == $this->checkInput($arr))
		{
			 return false;
		}
		else
		{
			$kickOff = $this->kickOffOneNumber($this->statNumber($arr));
			if (self::MAX_PROD_IS_ZERO === $kickOff)
			{
				return 0;
			}
			else
			{
				return $this->calcProd($arr, $kickOff);
			}
		}
	}

	/**
	 * 检查输入参数是否合法
	 * @param array $arr
	 * @return bool
	 */
	protected function checkInput(array $arr)
	{
		$arr_len = count($arr);
		if (2 > $arr_len)
		{
			return false;
		}
		else
		{
			for($i = 0; $i < $arr_len; $i++)
			{
				if (!is_int($arr[$i]))
				{
					return false;
				}
			}
			return true;
		}
	}


	/**
	 * 统计数组中正数、负数、零的个数和极值
	 * @param array $arr
	 * @return array
	 */
	public function statNumber(array $arr)
	{
		$amount_zero = 0;//零的个数
		$amount_negative = 0;//负数个数
		$amount_positive = 0;//正数个数
		$min_positive_index = null;
		$min_negative_index = null;
		$max_negative_index = null;
		$the_only_zero_index = null;

		$arr_len = count($arr);
		for($i = 0; $i < $arr_len; $i++)
		{
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
		return array(
			"az" => $amount_zero,
			"an" => $amount_negative,
			"ap" => $amount_positive,
			"min_p" => $arr[$min_positive_index],
			"min_n" => $arr[$min_negative_index],
			"max_n" => $arr[$max_negative_index],
		);
	}

	/**
	 * 找出应该排除哪个数
	 * @param array $arr
	 * @return int|string
	 */
	public function kickOffOneNumber(array $arr)
	{
		$returnValue = null;
		if (1 < $arr["az"])
		{
			/*
			 * 0的个数大于1，任意取N-1个元素，其乘积都是0
			 * 故无须再判断正数和负数的个数
			 * 排除任意一个数即可
			 */
			$returnValue = self::MAX_PROD_IS_ZERO;
		}
		else if (1 == $arr["az"])
		{
			if (1 == $arr["an"] % 2)
			{//奇数个负数
				/*
				 * 最大乘积只能是0，无需判断正数个数
				 */
				$returnValue = self::MAX_PROD_IS_ZERO;
			} else {//偶数个负数
				/*
				 * 除0之外的N-1个整数乘积最大
				 */
				$returnValue = 0;
			}
		}
		else// if (1 > $arr["az"])
		{
			if (1 == $arr["an"] % 2)//奇数个负数
			{
				/*
				 * 除【绝对值最小的负数】之外的N-1个整数乘积最大
				 */
				$returnValue = $arr["max_n"];
			}
			else//偶数个负数
			{
				if (0 < $arr["ap"])
				{//存在正数
					/*
					 * 除【绝对值最小的正数】之外的N-1个整数乘积最大
					 */
					$returnValue = $arr["min_p"];
				}
				else
				{
					/*
					 * 除【绝对值最大的负数】之外的N-1个整数乘积最大
					 * 乘积为负
					 */
					$returnValue = $arr["min_n"];
				}
			}
		}
		return $returnValue;
	}

	/**
	 * 计算乘积
	 * @param array $arr
	 * @param       $kickOff
	 * @return int
	 */
	protected function calcProd(array $arr, $kickOff)
	{
		$kicked = false;
		$arr_len = count($arr);
		$prod = 1;
		for($i = 0; $i < $arr_len; $i++)
		{
			if ($arr[$i] != $kickOff || true == $kicked)
			{
				$prod *= $arr[$i];
			}
			else
			{
				$kicked = true;
			}
		}
		return $prod;
	}
}