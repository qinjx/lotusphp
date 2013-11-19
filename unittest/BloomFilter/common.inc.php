<?php
$lotusHome = substr(__FILE__, 0, strpos(__FILE__, "unittest"));
require_once $lotusHome . "unittest/unittest_util.func.php";
require_once $lotusHome . "runtime/BloomFilter/BloomFilter.php";

/**
 * 用这个类把LtAutoloader的protected属性和方法暴露出来测试
 */
class LtBloomFilterProxy extends LtBloomFilter
{
	public function __get($prop)
	{
		if (isset($this->$prop))
		{
			return $this->$prop;
		}
	}

	public function hash($str, $bucketSize, $magicNum)
	{
		return parent::hash($str, $bucketSize, $magicNum);
	}
}