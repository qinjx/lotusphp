<?php
/**
 * StoreFile
 * @author Jianxiang Qin <TalkativeDoggy@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause New BSD License
 * @version svn:$Id: StoreFile.php 969 2013-01-10 07:52:33Z talkativedoggy@gmail.com $
 */

/**
 * LtStore File
 * @author Jianxiang Qin <TalkativeDoggy@gmail.com>
 * @category runtime
 * @package Lotusphp\Store
 */
class LtStoreFile implements LtStore
{
	/**
	 * 存储路径
	 * @var string
	 */
	public $storeDir;
	/**
	 * 前缀
	 * @var string
	 */
	public $prefix = 'LtStore';
	/**
	 * 是否使用序列化
	 * @var boolean
	 */
	public $useSerialize = false;
	/**
	 * 默认存储路径
	 * @var string
	 */
	static public $defaultStoreDir = "/tmp/LtStoreFile/";
	
	/**
	 * init
	 */
	public function init()
	{
		if (null == $this->storeDir)
		{
			$this->storeDir = self::$defaultStoreDir;
		}
		$this->storeDir = str_replace('\\', '/', $this->storeDir);
		$this->storeDir = rtrim($this->storeDir, '\\/') . '/';
	}

	/**
	 * 当key存在时:
	 * 如果没有过期, 不更新值, 返回 false
	 * 如果已经过期,   更新值, 返回 true
	 * @param string $key
	 * @param mixed $value
	 * @return boolean
	 */
	public function add($key, $value)
	{
		$file = $this->getFilePath($key);
		$cachePath = pathinfo($file, PATHINFO_DIRNAME);
		if (!is_dir($cachePath))
		{
			if (!@mkdir($cachePath, 0777, true))
			{
				trigger_error("Can not create $cachePath");
			}
		}
		if (is_file($file))
		{
			return false;
		}
		if ($this->useSerialize)
		{
			$value = serialize($value);
		}
		$length = file_put_contents($file, '<?php exit;?>' . $value);
		return $length > 0 ? true : false;
	}

	/**
	 * 删除不存在的key返回false
	 * @param string $key
	 * @return boolean
	 */
	public function del($key)
	{
		$file = $this->getFilePath($key);
		if (!is_file($file))
		{
			return false;
		}
		else
		{
			return @unlink($file);
		}
	}

	/**
	 * 取不存在的key返回false
	 * 已经过期返回false
	 * 成功返回数据,失败返回false
	 * @param string $key
	 * @return boolean 
	 */
	public function get($key)
	{
		$file = $this->getFilePath($key);
		if (!is_file($file))
		{
			return false;
		}
		$str = file_get_contents($file);
		$value = substr($str, 13);
		if ($this->useSerialize)
		{
			$value = unserialize($value);
		}
		return $value;
	}

	/**
	 * key不存在 返回false
	 * 不管有没有过期,都更新数据
	 * @param string $key
	 * @param string|array|obj $value
	 * @return boolean
	 */
	public function update($key, $value)
	{
		$file = $this->getFilePath($key);
		if (!is_file($file))
		{
			return false;
		}
		else
		{
			if ($this->useSerialize)
			{
				$value = serialize($value);
			}
			$length = file_put_contents($file, '<?php exit;?>' . $value);
			return $length > 0 ? true : false;
		}
	}

	/**
	 * 目录哈希
	 * @param string $key
	 * @return string
	 */
	public function getFilePath($key)
	{
		$token = md5($key);
		return $this->storeDir .
		$this->prefix . '/' .
		substr($token, 0, 2) .'/' .
		substr($token, 2, 2) . '/' .
		$token . '.php';
	}
}
