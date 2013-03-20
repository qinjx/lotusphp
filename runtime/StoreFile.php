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
	 * 默认存储路径
	 * @var string
	 */
	static public $defaultStoreDir = "/tmp/LtStoreFile/";

    /**
     * 是否初始化过
     */
    private $hasBeenInited = false;

    /**
	 * init
	 */
	public function init()
	{
		if (null == $this->storeDir)
		{
			$this->storeDir = self::$defaultStoreDir;
		}
        else if (isset($_SERVER["DOCUMENT_ROOT"]) && false !== strpos($this->storeDir, $_SERVER["DOCUMENT_ROOT"]))
        {
            trigger_error("don't put store dir under doc_root");
        }
		$this->storeDir = str_replace('\\', '/', $this->storeDir);
		$this->storeDir = rtrim($this->storeDir, '\\/') . '/';
        $this->hasBeenInited = true;
	}

	/**
	 * 当key存在时, 返回 false
	 * @param string $key
	 * @param mixed $value
	 * @return boolean
	 */
	public function add($key, $value)
	{
        if ($this->hasBeenInited)
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
		$length = file_put_contents($file, serialize($value));
		return $length > 0 ? true : false;
        }
        else
        {
            trigger_error("init() method must be called");
            return false;
        }
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
			return unlink($file);
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
		$value = unserialize($str);
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
			$length = file_put_contents($file, serialize($value));
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
		$token;
	}
}
