<?php
/**
 * Autoloader
 * @author Jianxiang Qin <TalkativeDoggy@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause New BSD License
 * @version svn:$Id: Autoloader.php 979 2013-01-29 09:24:06Z talkativedoggy@gmail.com $
 * @package Lotusphp\Autoloader
 */

/**
 * 自动加载类和函数
 * 
 * 按需加载类，每次只加载用到的类。
 * 
 *     函数库文件不是按需加载！若支持加载函数，则所有定义函数的文件都会加载。
 * 
 * 代码中用到一个类或者函数的时候，不需要使用include/require来包含类库文件或者函数库文件。
 * 
 * 基于Autoloader组件的代码中将不用使用include/require。
 * 
 * Autoloader缓存的是绝对路径，能让Opcode Cache有效缓存文件。
 * 
 *     Autoloader要求类的名字唯一，不在意类文件的路径和文件名。目前不支持命名空间(PHP5.3)
 * 
 * 传统的include/require通常存在以下问题。
 * <ul>
 * <li>目录名和文件名变化引起程序代码变化。</li>
 * <li>Windows和Linux对文件路径的大小写和目录分隔符号的处理不同导致代码在不同平台迁移时出现问题。</li>
 * <li>include_path相对路径的性能低（显著地低）。</li>
 * <li>为了保证不重复包含，使用include_once和require_once导致效率低（不是显著的低）。</li>
 * </ul>
 * @author Jianxiang Qin <TalkativeDoggy@gmail.com> Yi Zhao <zhao5908@gmail.com>
 * @category runtime
 * @package Lotusphp\Autoloader
 */
class LtAutoloader
{
	/**
	 * @var array 要扫描的文件类型
	 * 若该属性设置为array("php","inc","php3")，
	 * 则扩展名为"php","inc","php3"的文件会被扫描，
	 * 其它扩展名的文件会被忽略
	 */
	public $allowFileExtension = array('php', 'inc');
	
	/**
	 * @var array 不扫描的目录
	 * 若该属性设置为array(".svn", ".setting")，
	 * 则所有名为".setting"的目录也会被忽略
	 */
	public $skipDirNames = array('.svn', '.git', '.DS_Store');

	/** @var LtStoreFile 存储句柄默认使用 @link LtStoreFile */
	public $storeHandle;
	
	/** @var array 指定需要自动加载的目录列表 */
	public $autoloadPath;
	
	/** @var bool
     * true 开发模式下  每次都会扫描目录列表
     * false 生产环境下 只扫描一次
     */
	public $devMode = true;
	
	/** @var array 函数名 -> 文件路径  映射 */
	private $functionFileMapping = array();

    /** @var array 类名 -> 文件路径  映射 */
    private $classFileMapping = array();

    /** @var array 定义了函数的文件列表 */
    private $functionFiles = array();

    /** @var LtStoreFile 持久存储句柄,存储文件的get_token_all分析结果/filesize/filehash @link LtStoreFile */
    private $persistentStoreHandle;

    /** @var int store name space id */
    private $storeNameSpaceId;

	/**
	 * 递归扫描指定的目录列表，加载全部的函数定义文件。
	 * 注册自动加载函数，按需加载类文件。
	 * @return void
	 */
	public function init()
	{
        $this->storeNameSpaceId = sprintf("%u", crc32(serialize($this->autoloadPath)));

        if (true != $this->devMode)
        {
            if ($this->storeHandle instanceof LtStore)
            {
                $this->storeHandle->prefix = 'Lt-Autoloader-' . $this->storeNameSpaceId;
            }
            else
            {
                if (null == $this->storeHandle)
                {
                    $this->storeHandle = new LtStoreFile;
                    $this->storeHandle->prefix = 'Lt-Autoloader-' . $this->storeNameSpaceId;
                    $this->storeHandle->init();
                }
                else
                {
                    trigger_error("You passed a value to autoloader::storeHandle, but it is NOT an instance of LtStore");
                }
            }
        }
        else
        {
            $this->storeHandle = new LtStoreMemory;
        }

		// Whether scanning directory
		if ($storedMap = $this->storeHandle->get("map"))
        {
            $this->classFileMapping = $storedMap["classes"];
            $this->functionFiles = $storedMap["functions"];
        }
        else
		{
            $this->setPersistentStoreHandle();
			$autoloadPath = $this->preparePath($this->autoloadPath);
			foreach($autoloadPath as $path)
			{
				if (is_file($path))
				{
					$this->addFileMap($path);
				}
			}
			$this->scanDirs($autoloadPath);
			unset($autoloadPath);
		}

		// Whether loading function files
		$this->loadFunctionFiles();
		spl_autoload_register(array($this, "loadClass"));
	}

    protected function setPersistentStoreHandle()
    {
        $this->persistentStoreHandle = new LtStoreFile;
        $this->persistentStoreHandle->prefix = 'Lt-parsed-token-' . $this->storeNameSpaceId;
        $this->persistentStoreHandle->init();
    }

	/**
	 * 自动include所有定义了函数的php文件。
     * 因为PHP的Autoload机制是针对Class的.function文件没有办法按需加载
	 * @return void
	 */
	protected function loadFunctionFiles()
	{
		if (count($this->functionFiles))
		{
			foreach ($this->functionFiles as $functionFile)
			{
				include_once($functionFile);
			}
		}
	}

	/**
	 * 被注册的自动加载函数
	 * @param string $className
	 * @return void 
	 */
	protected function loadClass($className)
	{
		if ($filePath = $this->getFilePathByClassName($className))
		{
			include($filePath);
		}
	}

	/**
	 * 将目录分隔符号统一成linux目录分隔符号/
	 * @param string $path
	 * @return boolean
	 */
	protected function convertPath($path)
	{
		$path = str_replace("\\", "/", $path);
		if (!is_readable($path))
		{
			trigger_error("Directory is not exists/readable: {$path}");
			return false;
		}
		$path = rtrim(realpath($path), '\\/');
		if (preg_match("/\s/i", $path))
		{
			trigger_error("Directory contains space/tab/newline is not supported: {$path}");
			return false;
		}
		return $path;
	}

	/**
	 * The string or an Multidimensional array into a one-dimensional array
	 * 将字符串和多维数组转换成一维数组
	 * @param mixed $paths
	 * @return array one-dimensional array
	 */
	protected function preparePath($paths)
	{
		$oneDPathArray = array();
		if (!is_array($paths))
		{
			$paths = array($paths);
		}
		$i = 0;
		while (isset($paths[$i]))
		{
			if (!is_array($paths[$i]) && $path = $this->convertPath($paths[$i]))
			{
				$oneDPathArray[] = $path;
			}
			else
			{
				foreach($paths[$i] as $v)
				{
					$paths[] = $v;
				}
			}
			$i ++;
		}
        unset($paths);
		return $oneDPathArray;
	}

	/**
	 * Using iterative algorithm scanning subdirectories
	 * save autoloader filemap
	 * 递归扫描目录包含子目录，保存自动加载的文件地图。
	 * @param array $dirs one-dimensional
	 * @return void
     * @todo in_array换成array_key_exists以提升性能(autoloadPath不能传文件名,要不单元测试代码不太好写)
	 */
	protected function scanDirs($dirs)
	{
		$i = 0;
		while (isset($dirs[$i]))
		{
			$dir = $dirs[$i];
			$files = scandir($dir);
			foreach ($files as $file)
			{
                $currentFile = $dir . DIRECTORY_SEPARATOR . $file;
                if (is_file($currentFile))
                {
                    $this->addFileMap($currentFile);
                }
                else if (is_dir($currentFile))
                {
                    if (in_array($file, array(".", "..")) || in_array($file, $this->skipDirNames))
                    {
                        continue;
                    }
                    else
                    {
                        // if $currentFile is a directory, pass through the next loop.
                        $dirs[] = $currentFile;
                    }
                }
                else
                {
                    trigger_error("$currentFile is not a file or a directory.");
                }
			} //end foreach
			$i ++;
		} //end while

        $this->functionFiles = array_unique(array_values($this->functionFileMapping));
        $map = array("classes" => $this->classFileMapping, "functions" => $this->functionFiles);
        if ($this->storeHandle->get("map"))
        {
            $this->storeHandle->update("map", $map);
        }
        else
        {
            $this->storeHandle->add("map", $map);
        }
	}

    /**
     * 分析出字符串中的类，接口，函数。 
     * @param string $src
     * @return array
     * @todo 若当前文件包含了直接执行的php语句,或者html,输出警告
     * @todo 若类库文件没有省略唯一一个“?>”标签，输出警告
     * @todo 若当前文件有语法错误,抛出异常
     */
	protected function parseLibNames($src)
	{
		$libNames = array();
		$tokens = token_get_all($src);
		$level = 0;
		$found = false;
		$name = '';
		foreach ($tokens as $token)
		{
			if (is_string($token))
			{
				if ('{' == $token)
				{
					$level ++;
				}
				else if ('}' == $token)
				{
					$level --;
				}
			}
			else
			{
				list($id, $text) = $token;
				if (T_CURLY_OPEN == $id || T_DOLLAR_OPEN_CURLY_BRACES == $id)
				{
					$level ++;
				}
				if (0 < $level)
				{
					continue;
				}
				switch ($id)
				{
					case T_STRING:
						if ($found)
						{
							$libNames[strtolower($name)][] = $text;
							$found = false;
						}
						break;
					case T_CLASS:
					case T_INTERFACE:
					case T_FUNCTION:
						$found = true;
						$name = $text;
						break;
				}
			}
		}
		return $libNames;
	}

	/**
	 * 保存类名、接口名和对应的文件绝对路径。 
	 * @param string $className
	 * @param string $file
	 * @return boolean
	 */
	protected function addClass($className, $file)
	{
		$key = strtolower($className);
		if (isset($this->classFileMapping[$key]))
		{
            $existedClassFile = $this->classFileMapping[$key];
			trigger_error("duplicate class [$className] found in:\n$existedClassFile\n$file\n, or please clear the cache");
			return false;
		}
		else
		{
            $this->classFileMapping[$key] = $file;
			return true;
		}
	}

	/**
	 * 保存函数名和对应的文件绝对路径
	 * @param string $functionName
	 * @param string $file
	 * @return boolean
	 */
	protected function addFunction($functionName, $file)
	{
		$functionName = strtolower($functionName);
		if (isset($this->functionFileMapping[$functionName]))
		{
			$existedFunctionFile = $this->functionFileMapping[$functionName];
			trigger_error("duplicate function [$functionName] found in:\n$existedFunctionFile\n$file\n");
			return false;
		}
		else
		{
			$this->functionFileMapping[$functionName] = $file;
			return true;
		}
	}

	/**
	 * 将文件添加到自动加载的FileMap，
	 * 添加之前会判断自从上次扫描后有没有修改，若没有修改则无需重复添加，
	 * 若修改过，则分析文件内容，根据内容中包含的类、接口，函数添加到FileMap
	 * @param string $filePath
	 * @return boolean
	 */
	protected function addFileMap($filePath)
	{
        if (!in_array(pathinfo($filePath, PATHINFO_EXTENSION), $this->allowFileExtension))
        {//init()会调用这个方法, 不要将这个判断移动到scanDir()中
            return false;
        }
        $sourceContent = file_get_contents($filePath);
        $fileSize = filesize($filePath);
        $fileChecksum = crc32($sourceContent);

        $savedFileInfo = $this->persistentStoreHandle->get($filePath);
		if (!isset($savedFileInfo['file_size']) || $savedFileInfo['file_size'] != $fileSize || $savedFileInfo['file_checksum'] != $fileChecksum)
		{
            if($libNames = $this->parseLibNames($sourceContent))
            {
                $newFileInfo = array('file_size' => $fileSize, 'file_checksum' => $fileChecksum, 'lib_names' => $libNames);
                if (isset($savedFileInfo['file_size']))
                {
                    $this->persistentStoreHandle->update($filePath, $newFileInfo);
                }
                else
                {
                    $this->persistentStoreHandle->add($filePath, $newFileInfo);
                }
            }
			else
            {
                /*
                 * Can't find any class/function in file: $filePath
                 * But, don't send error message, this should be done by PHP Code Sniffer
                 */
            }
		}
        else
        {
            $libNames = $savedFileInfo['lib_names'];
        }

        foreach ($libNames as $libType => $libArray)
        {
            foreach ($libArray as $libName)
            {
                if ("class" == $libType)
                {
                    $this->addClass($libName, $filePath);
                }
                else
                {
                    $this->addFunction($libName, $filePath);
                }
            }
        }
		return true;
	}

    protected function getFilePathByClassName($className)
    {
        $key = strtolower($className);
        if (isset($this->classFileMapping[$key]))
        {
            return $this->classFileMapping[$key];
        }
        else
        {
            return false;
        }
    }
}
