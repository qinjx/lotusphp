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
     * @todo 允许用户设置服务哭环境，是虚拟主机还是独立服务器。如果是虚拟主机，store dir可以在doc_root下，后缀使用php
	 * @var string
	 */
	static public $defaultStoreDir = "/tmp/LtStoreFile/";

    /**
     * 是否初始化过
     */
    private $hasBeenInitialized = false;

    /**
	 * init
	 */
	public function init()
	{
		if (null == $this->storeDir)
		{
            self::setDefaultStoreDir();
			$this->storeDir = self::$defaultStoreDir;
		}
        else if (isset($_SERVER["DOCUMENT_ROOT"]) && !empty($_SERVER["DOCUMENT_ROOT"]) && false !== strpos($this->storeDir, $_SERVER["DOCUMENT_ROOT"]))
        {
            trigger_error("don't put store dir under doc_root");
        }
		$this->storeDir = str_replace('\\', '/', $this->storeDir);
		$this->storeDir = rtrim($this->storeDir, '\\/') . '/';
        $this->hasBeenInitialized = true;
	}

	/**
	 * 当key存在时, 返回 false
	 * @param string $key
	 * @param mixed $value
	 * @return boolean
	 */
	public function add($key, $value)
	{
		$file = $this->getFilePath($key);
        if (is_file($file))
        {
            return false;
        }
		$cachePath = pathinfo($file, PATHINFO_DIRNAME);
		if (!file_exists($cachePath))
		{
            mkdir($cachePath, 0777, true);
		}
		$length = file_put_contents($file, serialize($value));
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
			return unlink($file);
		}
	}

	/**
	 * 取不存在的key返回false
	 * 已经过期返回false
	 * 成功返回数据,失败返回false
	 * @param string $key
	 * @return mixed | null
	 */
	public function get($key)
	{
		$file = $this->getFilePath($key);
		if (!is_file($file))
		{
			return null;
		}
		$str = file_get_contents($file);
		$value = unserialize($str);
		return $value;
	}

	/**
	 * key不存在 返回false
	 * 不管有没有过期,都更新数据
	 * @param string $key
	 * @param mixed $value
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
	 * 根据key计算存储文件路径
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

    /**
     * 设置默认存储目录
     * @param string $dir
     * @return boolean
     */
    public static function setDefaultStoreDir($dir = null)
    {
        if (null === $dir)
        {
            $dir = sys_get_temp_dir();
        }
        self::$defaultStoreDir = $dir;
        return true;
    }
}
