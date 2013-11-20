<?php
/**
 * Class LtBloomFilter
 * @todo 使用integer array模拟一个bit array，减少内存占用
 */
class LtBloomFilter {
	protected $bitArray;
	protected $bitArrayMaxLength = 1048576;//1048576 = 1024 * 1024 = 1 Mega
	protected $imageFile;
	protected $syncCounter = 0;
	protected $syncThreshHold = 1;

	protected $magicNumbers = array(11,13,17,19,23,29,31,37,41,43,47,53,59,61);

	/**
	 * 设置待存储的元素数量
	 * @param integer $num，如要存储1亿个URL，就传1亿
	 * @return void
	 *
	 * BitArray长度是待存储元素的20倍，以降低误判率
	 */
	public function setBucketSize($num) {
		if (is_int($num) && 0 < $num) {
			$this->bitArrayMaxLength = bcmul("20", $num);
		} else {
			trigger_error("Bucket Size must be integer, and greater than 0", E_USER_ERROR);
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
	 * 若image file存在，且有上次持久化的内容，读取之
	 */
	public function init() {
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
		$magicNumTotal = count($this->magicNumbers);
		$bitArrayChanged = false;
		for ($i = 0; $i < $magicNumTotal; $i++) {
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
	 * 而返回true，则是很有可能不存在，之所以不是【一定存在】，是因为bloom filter算法自身允许一定的false positive
	 * false positive rate参见：http://en.wikipedia.org/wiki/Bloom_filter
	 */
	public function has($str) {
		$magicNumTotal = count($this->magicNumbers);
		for ($i = 0; $i < $magicNumTotal; $i++) {
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