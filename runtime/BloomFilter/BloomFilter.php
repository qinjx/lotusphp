<?php
/**
 * Class LtBloomFilter
 * @todo 使用integer array模拟一个bit array，减少内存占用
 */
class LtBloomFilter {
	protected $bucketSize;
	protected $errorRate = 0.000001;
	protected $imageFile;
	protected $syncThreshHold = 1;

	protected $bitArray;
	protected $bitArrayMaxLength;
	protected $syncCounter = 0;
	protected $magicNumbers = array(
		2,3,5,7,11,13,17,19,23,29,
		31,37,41,43,47,53,59,61,67,71,
		73,79,83,89,97,101,103,107,109,113
	);
	protected $hashFunAmountToUse;

	/**
	 * 设置待存储的元素数量上限
	 * @param integer $size，如要计划存储1亿个URL，就传1亿
	 * @return void
	 */
	public function setBucketSize($size) {
		if (is_int($size) && 0 < $size) {
			$this->bucketSize = $size;
		} else {
			trigger_error("Bucket Size must be integer, and greater than 0", E_USER_ERROR);
		}
	}

	/**
	 * 设置误判率上限
	 * @param float $rate
	 * 最小值：1.0E10-9，即十亿分之一，十亿条数据中仅一条误判
	 */
	public function setErrorRate($rate) {
		$minErrorRate = 0.000000001;
		if (is_float($rate) && $minErrorRate <= $rate && $rate < 1) {
			$this->errorRate = $rate;
		} else {
			trigger_error("Bucket Size must be float number between $minErrorRate and 1.0", E_USER_ERROR);
		}
	}

	/**
	 * 设置持久化所用的镜像文件
	 * @param string $filePath
	 *
	 * 以定时将内存中的BitArray存储到硬盘上
	 */
	public function setImageFile($filePath) {
		if (!is_file($filePath)) {
			$dir = dirname($filePath);
			if (!is_dir($dir)) {
				if (is_writable(dirname($dir))) {
					mkdir($dir, 0777, true);
				} else {
					trigger_error("Dir [$dir] not exists and its parent dir not writable", E_USER_ERROR);
				}
			} else if (!is_writable($dir)) {
				trigger_error("dir [$dir] not writable", E_USER_ERROR);
			}
			touch($filePath);
		} else if (!is_readable($filePath) || !is_writable($filePath) ){
			trigger_error("image file [$filePath] not readable or writable", E_USER_ERROR);
		}
		$this->imageFile = $filePath;
	}

	/**
	 * 设置持久化的元素间隔，默认值为1
	 * @param integer $num
	 *
	 * 设为10，则每add() 10个元素，往硬盘上写一次
	 */
	public function setSyncThreshHold($num) {
		if (is_int($num) && 0 < $num) {
			$this->syncThreshHold = $num;
		} else {
			trigger_error("Sync Thresh Hold must be integer, and greater than 0", E_USER_ERROR);
		}
	}

	/**
	 * 初始化方法，每个Lotusphp组件都有的标准方法
	 *
	 * m,n,p,k计算关系参见：http://en.wikipedia.org/wiki/Bloom_filter#Probability_of_false_positives
	 */
	public function init() {
		//根据n, p算m
		$this->bitArrayMaxLength = bcmul("-1",
			bcmul((string) $this->bucketSize, (string) log($this->errorRate) / pow(log(2), 2))
		);

		//根据m,n算k
		$this->hashFunAmountToUse = ceil((float) bcdiv($this->bitArrayMaxLength, $this->bucketSize) * log(2));

		//若image file存在，且有上次持久化的内容，读取之
		if (!$this->imageFile) {
			$this->setImageFile(sys_get_temp_dir() . DIRECTORY_SEPARATOR . crc32(__FILE__) . ".bloom");
		} else if (0 < filesize($this->imageFile)) {
			$this->bitArray = unserialize(file_get_contents($this->imageFile));
		}
	}

	/**
	 * 加入字串
	 * @param string $str 要加入的字串
	 */
	public function add($str) {
		$bitArrayChanged = false;
		for ($i = 0; $i < $this->hashFunAmountToUse; $i++) {
			$hash = $this->hash($str, $this->bitArrayMaxLength, $this->magicNumbers[$i]);
			if (!isset($this->bitArray[$hash])) {
				$this->bitArray[$hash] = 1;
				$bitArrayChanged = true;
			}
		}
		if ($bitArrayChanged) {
			$this->syncCounter ++;
			if ($this->syncCounter >= $this->syncThreshHold) {
				$this->saveToDisk();
			}
		}
	}

	/**
	 * 判断某字串是否存在
	 * @param string $str
	 * @return bool
	 * has()方法返回false，说明一定不存在
	 * 而返回true，则表示存在（有极小可能误判）之所以不是【一定存在】，是因为bloom filter算法自身允许一定的false positive
	 */
	public function has($str) {
		for ($i = 0; $i < $this->hashFunAmountToUse; $i++) {
			$hash = $this->hash($str, $this->bitArrayMaxLength, $this->magicNumbers[$i]);
			if (!isset($this->bitArray[$hash])) {
				return false;
			}
		}
		return true;
	}

	/**
	 * @param string $str
	 * @param integer $bucketSize
	 * @param integer $magicNum
	 * @return integer
	 *
	 * 此hash算法借鉴自PHP内置的hash算法：https://github.com/php/php-src/blob/master/Zend/zend_hash.h
	 * 但由于PHP整数边界的问题，使用bcmath来运算，也就不能使用DJBX33A算法了
	 */
	protected function hash($str, $bucketSize, $magicNum) {
		$strLen = strlen($str);
		$hash = 5381;
		for ($i = 0; $i < $strLen; $i ++) {
			$hash = bcadd(
				(string) ord($str[$i]),
				bcmul((string) $magicNum, $hash)
			);
		}
		return bcmod($hash, (string) $bucketSize);
	}

	/*
	 * 数组持久化
	 */
	protected function saveToDisk() {
		file_put_contents($this->imageFile, serialize($this->bitArray));
	}
}