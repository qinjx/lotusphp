<?php
class LtBloomFilter {
	protected $stack;
	protected $bucketSize = 5242880;//5242880 = 5 * 1024 * 1024
	protected $imageFile;

	protected $magicNumbers = array(
//		array(3, -1),//7 = 2^3 - 1
		array(4, 1),//17 = 2^4 + 1
		array(5, -1),//31 = 2^5 - 1
		array(5, 1),//33 = 2^5 - 1
		array(6, 1),//65 = 2^6 + 1
		array(7, -1),//127 = 2^7 - 1
		array(8, 1),//257 = 2^8 + 1
		array(9, -1),//511 = 2^9 - 1
		array(9, 1),//513 = 2^9 - 1
//		array(10, -1),//1023 = 2^10 - 1
//		array(11, -1)//2047 = 2^11 - 1
	);

	public function setBucketSize($num) {

	}

	public function setImageFile($filePath) {

	}

	public function init() {

	}

	public function add($str) {
		$magicNumTotal = count($this->magicNumbers);
		for ($i = 0; $i < $magicNumTotal; $i++) {
			$hash = $this->hash($str, $this->bucketSize, $this->magicNumbers[$i][0], $this->magicNumbers[$i][1]);
			if (!isset($this->stack[$hash])) {
				$this->stack[$hash] = 1;
			}
		}
	}

	/**
	 * @param string $str
	 * @return bool
	 * has()方法返回false，说明一定不存在
	 * 而返回true，则是很有可能不存在，之所以不是【一定存在】，是因为bloom filter算法自身允许一定的false postive
	 * false postive rate参见：http://en.wikipedia.org/wiki/Bloom_filter
	 */
	public function has($str) {
		$magicNumTotal = count($this->magicNumbers);
		for ($i = 0; $i < $magicNumTotal; $i++) {
			$hash = $this->hash($str, $this->bucketSize, $this->magicNumbers[$i][0], $this->magicNumbers[$i][1]);
			if (!isset($this->stack[$hash])) {
				return false;
			}
		}
		return true;
	}

	/**
	 * @param string $str
	 * @param integer $bucketSize
	 * @param integer $magicNumIndex
	 * @param integer $magicNumOffset
	 * @return integer
	 * 此hash算法借鉴自PHP内置的hash算法：https://github.com/php/php-src/blob/master/Zend/zend_hash.h
	 */
	protected function hash($str, $bucketSize, $magicNumIndex, $magicNumOffset) {
		$strLen = strlen($str);
		$hash = 5381;
		for ($i = 0; $i < $strLen; $i ++) {
			if (1 == $magicNumOffset) {
				$hash = ($hash << $magicNumIndex) + $hash + ord($str[$i]);
			} else {
				$hash = ($hash << $magicNumIndex) - $hash + ord($str[$i]);
			}
		}
		return $hash % $bucketSize;
	}
}