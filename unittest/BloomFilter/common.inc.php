<?php
$lotusHome = substr(__FILE__, 0, strpos(__FILE__, "unittest"));
require_once $lotusHome . "unittest/unittest_util.func.php";
require_once $lotusHome . "runtime/BloomFilter/BloomFilter.php";

/**
 * 用这个类把LtAutoloader的protected属性和方法暴露出来测试
 */
class LtBloomFilterProxy extends LtBloomFilter
{
    public $bitArray;
    public $bitArrayChanged;
    public $magicNumbers = array(
        2,3,5,7,11,13,17,19,23,29,
        31,37,41,43,47,53,59,61,67,71,
        73,79,83,89,97,101,103,107,109,113
    );

	public function __get($prop)
	{
		if (isset($this->$prop))
		{
			return $this->$prop;
		}
	}

    public function __set($prop, $val)
    {
        $this->$prop = $val;
    }

	public function hash($str, $bitArrayMaxLength, $magicNum)
	{
		return parent::hash($str, $bitArrayMaxLength, $magicNum);
	}

    public function saveToDisk($bitArr, $file)
    {
        return parent::saveToDisk($bitArr, $file);
    }

    public function loadFromDisk($file)
    {
        return parent::loadFromDisk($file);
    }

    public function bitSet(&$arr, $k)
    {
        return parent::bitSet($arr, $k);
    }

    public function isBitSet(&$arr, $k)
    {
        return parent::isBitSet($arr, $k);
    }

    public function calcKeyAndPos($k)
    {
        return parent::calcKeyAndPos($k);
    }
}