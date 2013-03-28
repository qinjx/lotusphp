<?php
/**
 * 哎呀，画脑力毕竟不方便，一个开发维护一份脑图，一份测试代码，总有一次更新会忘了其中一个的
 * 那咱们用@see注释把它们融合到一个IDE中来吧
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