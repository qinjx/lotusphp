<?php
/**
 * Class LtBloomFilter
 */
class LtBloomFilter {
	protected $bucketSize;
	protected $errorRate = 0.000001;
	protected $imageFile;
	protected $syncThreshHold = 1;

	protected $bitArray = array();
	protected $bitArrayChanged = array();
	protected $bitArrayMaxLength;
	protected $syncCounter = 0;
	protected $magicNumbers = array(
		2,3,5,7,11,13,17,19,23,29,
		31,37,41,43,47,53,59,61,67,71,
		73,79,83,89,97,101,103,107,109,113
	);
	protected $hashFunAmountToUse;
	protected $hasBeenInitialized = false;

	/**
	 * 设置待存储的元素数量上限
	 * @param integer $size，如要计划存储1亿个URL，就传1亿
	 * @return void
	 */
	public function setBucketSize($size) {
		if (is_int($size) && 0 < $size) {
			$this->bucketSize = $size;
		} else {
			trigger_error("Bucket Size must be integer between 1 and " . PHP_INT_MAX . "(PHP_INT_MAX)", E_USER_ERROR);
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
	 * @todo 切割成多个文件存储，以避免文件太大时fseek()失效
	 *
	 * 以定时将内存中的BitArray存储到硬盘上
	 */
	public function setImageFile($filePath) {
		if (!is_string($filePath)) {
			trigger_error("$filePath must be a string", E_USER_ERROR);
		} else if (!is_file($filePath)) {
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
		if ($this->bucketSize) {
			//根据n, p算m
			$this->bitArrayMaxLength = bcmul("-1",
				bcmul((string) $this->bucketSize, (string) log($this->errorRate) / pow(log(2), 2))
			);

			//根据m,n算k
			$this->hashFunAmountToUse = ceil((float) bcdiv($this->bitArrayMaxLength, $this->bucketSize) * log(2));


			if (!$this->imageFile) {
				$this->setImageFile(sys_get_temp_dir() . DIRECTORY_SEPARATOR . crc32(__FILE__) . ".bloom");
			} else if (0 < filesize($this->imageFile)) {//若image file存在，且有上次持久化的内容，读取之
				$this->loadFromDisk($this->imageFile);
			}
			$this->hasBeenInitialized = true;
		} else {
			trigger_error("Please call setBucketSize() before init()", E_USER_ERROR);
		}
	}

	/**
	 * 加入字串
	 * @param string $str 要加入的字串
	 * @return boolean
	 */
	public function add($str) {
		if ($this->hasBeenInitialized) {
			if (is_string($str)) {
				for ($i = 0; $i < $this->hashFunAmountToUse; $i++) {
					$hash = $this->hash($str, $this->bitArrayMaxLength, $this->magicNumbers[$i]);
					if (!isset($this->bitArray[$hash])) {
						$this->bitArray[$hash] = 1;
						$this->bitArrayChanged[] = $hash;
					}
				}
				if (count($this->bitArrayChanged)) {
					$this->syncCounter ++;
					if ($this->syncCounter >= $this->syncThreshHold) {
						$this->saveToDisk($this->bitArrayChanged, $this->imageFile);
						$this->syncCounter = 0;
						$this->bitArrayChanged = array();
					}
				}
				return true;
			} else {
				return false;
			}
		} else {
			trigger_error("Please call init() first", E_USER_ERROR);
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
		if (is_string($str)) {
			for ($i = 0; $i < $this->hashFunAmountToUse; $i++) {
				$hash = $this->hash($str, $this->bitArrayMaxLength, $this->magicNumbers[$i]);
				if (!isset($this->bitArray[$hash])) {
					return false;
				}
			}
			return true;
		} else {
			return false;
		}
	}

	/**
	 * @param string $str
	 * @param string $bitArrayMaxLength
	 * @param integer $magicNum
	 * @return string bit integer
	 *
	 * 此hash算法借鉴自PHP内置的hash算法：https://github.com/php/php-src/blob/master/Zend/zend_hash.h
	 * 但由于PHP整数边界的问题，使用bcmath来运算，也就不能使用DJBX33A算法了
	 */
	protected function hash($str, $bitArrayMaxLength, $magicNum) {
		$strLen = strlen($str);
		$hash = 5381;
		for ($i = 0; $i < $strLen; $i ++) {
			$hash = bcadd(
				(string) ord($str[$i]),
				bcmul((string) $magicNum, $hash)
			);
		}
		return bcmod($hash, $bitArrayMaxLength);
	}

	/*
	 * 数组持久化
	 *
	 */
	/**
	 * 数组持久化
	 *
	 * @param array $arr 要持久化的数组
	 * @param string $file 文件路径（需先建好）
	 * @return bool
	 */
	protected function saveToDisk($arr, $file) {
		$fh = fopen($file, "r+");//注意，fopen($file, "a")会导致fseek失效，不能用，官方文档有说明
		foreach($arr as $k => $v) {
			fseek($fh, 0, SEEK_END);
			$fileSize = ftell($fh);
			$insertPos = $k * PHP_INT_SIZE;
			if ($insertPos > $fileSize) {//此key之前没有持久化过，插入之，并补齐其和此前最后一个元素间的空隙
				$emptyToFill = $k - $fileSize / PHP_INT_SIZE;
				for ($i = 0; $i < $emptyToFill; $i ++) {
					fwrite($fh, pack("d", 0));
				}
			}
			fseek($fh, $insertPos);
			fwrite($fh, pack("d", $arr[$k]));
		}
		fclose($fh);
		return true;
	}

	/**
	 * 从持久化文件中加载数组
	 *
	 * @param $file string $file 文件路径（需先建好）
	 * @return array
	 */
	protected function loadFromDisk($file) {
		$fh = fopen($file, "r");
		fseek($fh, 0, SEEK_END);
		$fileSize = ftell($fh);
		$arrLen = $fileSize / PHP_INT_SIZE;
		$bitArray = array();
		for ($i = 0; $i < $arrLen; $i ++) {
			fseek($fh, $i * PHP_INT_SIZE);
			$unpackedArray = unpack("d", fread($fh, PHP_INT_SIZE));
			$num = $unpackedArray[1];
			if (0 < $num) {
				$bitArray[$i] = $num;
			}
		}
		fclose($fh);
		return $bitArray;
	}

	/**
	 * 设置BitArray的某位为1
	 * @param array ref $arr
	 * @param int $k
	 * @return bool
	 */
	protected function bitSet(&$arr, $k) {
		list($arrKey, $bitPos) = $this->calcKeyAndPos($k);
		$mask = 1 << ($bitPos - 1);
		if (isset($arr[$arrKey])) {
			$arr[$arrKey] = $arr[$arrKey] | $mask;
		} else {
			$arr[$arrKey] = $mask;
		}

		return true;
	}

	/**
	 * 查询BitArray的某位是否为1
	 *
	 * @param array ref $arr
	 * @param int $k
	 * @return bool
	 */
	protected function isBitSet(&$arr, $k) {
		list($arrKey, $bitPos) = $this->calcKeyAndPos($k);
		$mask = 1 << ($bitPos - 1);
		if (isset($arr[$arrKey]) && 0 < ($arr[$arrKey] & $mask)) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * 计算int数组索引和bit位置
	 *
	 * @param string $k bit integer
	 * @return array
	 */
	protected function calcKeyAndPos($k) {
		$bitWidth = (string) (8 * PHP_INT_SIZE - 1);

		//数组下标转换成bit位个数时，加1，使下面的逻辑过程更贴近现实世界的自然思维
		bcscale(2);
		$intArrayKey = ceil(
				bcdiv(bcadd($k, "1"), $bitWidth)
			) - 1;
		$bitPos = bcmod($k, $bitWidth) + 1;
		return array(
			$intArrayKey,
			$bitPos
		);
	}
}