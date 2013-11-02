<?php
/**
 * Lotus
 * @author Yi Zhao <zhao5908@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause New BSD License
 * @version svn:$Id: Lotus.php 964 2012-08-27 04:02:32Z zhao5908@gmail.com $
 */

/**
 * 胶水代码
 * @package Lotusphp\Lotus
 * @category runtime
 */
class Lotus
{
	/**
	 * Lotus Option
	 * array("proj_dir"=>string, "app_name"=>string, "autoload_dir"=>array|string)
	 * @var array 
	 */
	public $option;
	/**
	 * 是否开发模式
	 * @var boolean
	 */
	public $devMode = true;
	/**
	 * 缓存目录
	 * @var string 
	 */
	public $defaultStoreDir;
	/**
	 * LtConfig实例句柄
	 * @var LtConfig
	 */
	public $configHandle;

	/**
	 * 项目目录
	 * @var string
	 */
	protected $proj_dir;
	/**
	 * 应用目录
	 * @var string
	 */
	protected $app_dir;
	/**
	 * 缓存目录
	 * @var string
	 */
	protected $cache_dir;
	/**
	 * lotus框架runtime目录
	 * @var string
	 */
	protected $lotusRuntimeDir;
	/**
	 * 缓存类实例句柄
	 * @var LtStoreFile
	 */
	protected $coreCacheHandle;

	/**
	 * construct
	 */
	public function __construct()
	{
		$this->lotusRuntimeDir = dirname(__FILE__) . DIRECTORY_SEPARATOR;
	}

	/**
	 * init
	 */
	public function init()
	{
		$underMVC = false;
		if (isset($this->option["proj_dir"]) && !empty($this->option["proj_dir"]))
		{
			$this->proj_dir = rtrim($this->option["proj_dir"], '\\/') . '/';
			if (isset($this->option["app_name"]) && !empty($this->option["app_name"]))
			{
				$this->app_dir = $this->proj_dir . "app/" . $this->option["app_name"] . "/";
				$this->cache_dir = sys_get_temp_dir() . "/cache/";
				$underMVC = true;
			}
			else
			{
				trigger_error("Lotus option [app_name] is missing.");
			}
		}

		/**
		 * Load core component
		 */
		require_once $this->lotusRuntimeDir . "Store.php";
		require_once $this->lotusRuntimeDir . "StoreMemory.php";
		require_once $this->lotusRuntimeDir . "StoreFile.php";

		if (!empty($this->defaultStoreDir))
		{
			$this->cache_dir = $this->defaultStoreDir;
            $this->cache_dir = rtrim($this->cache_dir, '\\/') . '/';
		}

		/**
		 * accelerate LtAutoloader, LtConfig
		 */
		$this->coreCacheHandle = new LtStoreFile;
		$prefix = sprintf("%u", crc32(serialize($this->app_dir)));
		$this->coreCacheHandle->prefix = 'Lotus-' . $prefix;
		$this->coreCacheHandle->storeDir = $this->cache_dir;
		$this->coreCacheHandle->init();

		/**
		 * Init Autoloader, do this before init all other lotusphp component.
		 */
		$this->prepareAutoloader();

		/**
		 * init Config
		 */
		$this->prepareConfig();
		
		/**
		 * Run dispatcher when under MVC mode
		 */
		if ($underMVC)
		{
			$this->runMVC();
		}
	}

	/**
	 * Autoload all lotus components and user-defined libraries;
	 */
	protected function prepareAutoloader()
	{
		require_once $this->lotusRuntimeDir . "Autoloader/Autoloader.php";
		$autoloader = new LtAutoloader;
		// 设置工作模式
		$autoloader->devMode = $this->devMode;
		$autoloader->autoloadPath[] = $this->lotusRuntimeDir;
		if (isset($this->option["autoload_dir"]))
		{
			$autoloader->autoloadPath[] = $this->option["autoload_dir"];
		}
		if ($this->proj_dir)
		{
            foreach(array($this->proj_dir . 'lib', $this->app_dir . 'action', $this->app_dir . 'lib') as $dir)
            {
                if (is_dir($dir))
                {
                    $autoloader->autoloadPath[] = $dir;
                }
            }
		}

		$autoloader->storeHandle = clone $this->coreCacheHandle;
		$autoloader->storeHandle->prefix = $this->coreCacheHandle->prefix.'-cls';
		
		$autoloader->init();
	}

	/**
	 * prepare config
     * @todo 先加载proj/conf下的配置，再加载app/conf下的配置，并自动覆盖重复项
     * 注意避开array_merge的坑
     * 或者，至少做个判断，app/conf不存在，去找proj/conf，不能不加载配置文件
	 */
	protected function prepareConfig()
	{
		$this->configHandle = LtObjectUtil::singleton('LtConfig', false);
		if (!$this->devMode)
		{
			$configFile = 'conf/conf.php';
			$this->configHandle->storeHandle = clone $this->coreCacheHandle;
			$this->configHandle->storeHandle->prefix = $this->coreCacheHandle->prefix.'-conf';
		}
		else
		{
			$configFile = 'conf/conf_dev.php';
		}
		$this->configHandle->init();
		if ($this->app_dir && is_file($this->app_dir . $configFile))
		{
			$this->configHandle->loadConfigFile($this->app_dir . $configFile);
		}
	}

	/**
	 * run mvc
	 */
	protected function runMVC()
	{
		$router = LtObjectUtil::singleton('LtRouter', false);
		$router->init();
		$dispatcher = LtObjectUtil::singleton('LtDispatcher',false);
		$dispatcher->configHandle = $this->configHandle;
		$dispatcher->viewDir = $this->app_dir . 'view/';
		$dispatcher->projDir = $this->proj_dir;
		$dispatcher->appDir = $this->app_dir;

		$prefix = sprintf("%u", crc32(serialize($this->app_dir)));
		if (!$this->devMode)
		{
			// 生产环境下，修改模板文件后，必需手工删除模板引擎编译后的文件
			$dispatcher->viewTplDir = $this->cache_dir . 'Lotus-' . $prefix . '-tpl/';
			$dispatcher->viewTplAutoCompile = false;
		}
		else
		{
			// 开发模式下模板引擎比较源文件编译后的文件日期来决定是否重新编译
			$dispatcher->viewTplDir = $this->cache_dir . 'Lotus-' . $prefix . '-tpl-dev/';
			$dispatcher->viewTplAutoCompile = true;
		}
		$dispatcher->dispatchAction($router->module, $router->action);
	}
}
