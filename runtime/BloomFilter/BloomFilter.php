<?php
class LtBloomFilter {
	protected $stack;
	protected $bucketSize = 5242880;//5242880 = 5 * 1024 * 1024
	protected $imageFile;

	protected $magicNumbers = array(11,13,17,19,23,29,31,37,41,43);

	public function setBucketSize($num) {

	}

	public function setImageFile($filePath) {

	}

	public function init() {

	}

	public function add($str) {
		$magicNumTotal = count($this->magicNumbers);
		for ($i = 0; $i < $magicNumTotal; $i++) {
			$hash = $this->hash($str, $this->bucketSize, $this->magicNumbers[$i]);
			if (!isset($this->stack[$hash])) {
				$this->stack[$hash] = 1;
			}
		}
	}

	/*
	 * @param string $str
	 * @return bool
	 * has()方法返回false，说明一定不存在
	 * 而返回true，则是很有可能不存在，之所以不是【一定存在】，是因为bloom filter算法自身允许一定的false positive
	 * false postive rate参见：http://en.wikipedia.org/wiki/Bloom_filter
	 */
	public function has($str) {
		$magicNumTotal = count($this->magicNumbers);
		for ($i = 0; $i < $magicNumTotal; $i++) {
			$hash = $this->hash($str, $this->bucketSize, $this->magicNumbers[$i]);
			if (!isset($this->stack[$hash])) {
				return false;
			}
		}
		return true;
	}

	/*
	 * @param string $str
	 * @param integer $bucketSize
	 * @param integer $magicNum
	 * @param integer $magicNumOffset
	 * @return integer
	 * 此hash算法借鉴自PHP内置的hash算法：https://github.com/php/php-src/blob/master/Zend/zend_hash.h
	 */
	protected function hash($str, $bucketSize, $magicNum) {
		$strLen = strlen($str);
		$hash = 5381;
		for ($i = 0; $i < $strLen; $i ++) {
			$hash = bcadd(
				(string) ord($str[$i]),
				bcmul("$magicNum", $hash)
			);
		}

		return bcmod($hash, (string) $bucketSize);
	}
}