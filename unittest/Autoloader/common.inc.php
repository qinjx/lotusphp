<?php
$lotusHome = substr(__FILE__, 0, strpos(__FILE__, "unittest"));
require_once $lotusHome . "unittest/unittest_util.func.php";
require_once $lotusHome . "runtime/Store.php";
require_once $lotusHome . "runtime/StoreMemory.php";
require_once $lotusHome . "runtime/StoreFile.php";
require_once $lotusHome . "runtime/Autoloader/Autoloader.php";

/**
 * 用这个类把LtAutoloader的protected属性和方法暴露出来测试
 */
class LtAutoloaderProxy extends LtAutoloader
{
	public function __construct()
	{
		$this->storeHandle = new LtStoreMemory;
        $this->setPersistentStoreHandle();
	}
	public function __get($prop)
	{
		if (isset($this->$prop))
		{
			return $this->$prop;
		}
	}

	public function preparePath($path)
	{
		return parent::preparePath($path);
	}

	public function addClass($className, $file)
	{
		return parent::addClass($className, $file);
	}

	public function addFunction($functionName, $file)
	{
		return parent::addFunction($functionName, $file);
	}

	public function parseLibNames($src)
	{
		return parent::parseLibNames($src);
	}

	public function addFileMap($filename)
	{
		return parent::addFileMap($filename);
	}

	public function scanDirs($dir)
	{
		return parent::scanDirs($dir);
	}

	public function loadFunctionFiles()
	{
		return parent::loadFunctionFiles();
	}

	public function loadClass($className)
	{
		return parent::loadClass($className);
	}

    public function getFilePathByClassName($className)
    {
        return parent::getFilePathByClassName($className);
    }
}