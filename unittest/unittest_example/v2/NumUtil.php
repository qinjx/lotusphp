<?php
/**
 * 哎呀，上一版本的测试用例好像够多了，但是不能书面证明呢
 * 让我们在白板上画个脑图
 */
class NumUtil
{
	static public function findMaxProd(array $arr)
	{

		$arr_len = count($arr);
		$pick_out_index = null;

		//找出最小值的key
		for($i = 0; $i < $arr_len; $i++)
		{
				if (null == $pick_out_index || $arr[$i] < $arr[$pick_out_index])
				{
					$pick_out_index = $i;
				}
		}

		/**
		 * 计算除了最小值之外其它N-1个元素的乘积
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