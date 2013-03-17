<?php
/**
 * The Router class
 * @author Yi Zhao <zhao5908@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause New BSD License
 * @version svn:$Id: Router.php 964 2012-08-27 04:02:32Z zhao5908@gmail.com $
 */

/**
 * The Router class
 * @author Yi Zhao <zhao5908@gmail.com>
 * @category runtime
 * @package   Lotusphp\Router
 */
class LtRouter
{
	/** @var LtConfig config handle */
	public $configHandle;
	
	/** @var string module */
	public $module;
	
	/** @var string action */
	public $action;

	/** @var array default module action */
	private $default = array('module'=>'default', 'action'=>'index');
	
	/** @var string delimiter */
	private $delimiter = '-';
	
	/** @var string postfix */
	private $postfix = '.html';
	
	/** @var string For example REWRITE PATH_INFO STANDARD */
	private $protocol = 'STANDARD';

	/**
	 * construct
	 */
	public function __construct()
	{
		if (! $this->configHandle instanceof LtConfig)
		{
			if (class_exists("LtObjectUtil"))
			{
				$this->configHandle = LtObjectUtil::singleton("LtConfig");
			}
			else
			{
				$this->configHandle = new LtConfig;
			}
		}
	}

	/**
	 * init
	 */
	public function init()
	{
		$routingTable = $this->configHandle->get("router.routing_table");

		if (!empty($routingTable))
		{
			if (isset($routingTable['default']))
			{
				$this->default = $routingTable['default'];
			}
			if (isset($routingTable['delimiter']))
			{
				$this->delimiter = $routingTable['delimiter'];
			}
			if (isset($routingTable['postfix']))
			{
				$this->postfix = $routingTable['postfix'];
			}
			if (isset($routingTable['protocol']))
			{
				$this->protocol = $routingTable['protocol'];
			}
		}

		$this->protocol = strtoupper($this->protocol);
		$this->module = $this->default['module'];
		$this->action = $this->default['action'];

		if (isset($_SERVER['SERVER_PROTOCOL'])) // HTTP HTTPS
		{
			$this->routeFromWeb();
		}
		else // CLI
		{
			$this->routeFromCli();
		}
	}

	/**
	 * route from http https
	 */
	private function routeFromWeb()
	{
		switch ($this->protocol)
		{
			case 'REWRITE':
				if (! $this->isStandardUrl())
				{
					$url = $this->getRewriteUrl();
					$this->setUrlToGet($url);
				}
				break;
			case 'PATH_INFO':
				if (! $this->isStandardUrl())
				{
					$this->delimiter = '/';
					$url = $this->getPathInfoUrl();
					$this->setUrlToGet($url);
				}
				break;
			default :
				$this->delimiter = '';
				$this->postfix = '';
				break;
		}
		if (isset($_GET['module']))
		{
			$this->module = $_GET['module'];
		}
		else
		{
			$_GET['module'] = $this->module;
		}
		if (isset($_GET['action']))
		{
			$this->action = $_GET['action'];
		}
		else
		{
			$_GET['action'] = $this->action;
		}
	}

	/**
	 * is standard url
	 * @return boolean
	 */
	private function isStandardUrl()
	{
		if (strpos($_SERVER['REQUEST_URI'], '.php?module=') || strpos($_SERVER['REQUEST_URI'], '/?module='))
		{
			return true;
		}
		return false;
	}

	/**
	 * get rewrite url
	 * @return string
	 */
	private function getRewriteUrl()
	{
		if (strcmp($_SERVER['REQUEST_URI'], $_SERVER['SCRIPT_NAME']))
		{
			return substr($_SERVER['REQUEST_URI'], strlen(pathinfo($_SERVER['SCRIPT_NAME'], PATHINFO_DIRNAME)));
		}
		return '';
	}

	/**
	 * get path_info url
	 * 不使用$_SERVER['PATH_INFO']是因为多个//自动合并成一个/
	 * 
	 * @return string
	 */
	private function getPathInfoUrl()
	{
		return substr($_SERVER['REQUEST_URI'], strlen($_SERVER['SCRIPT_NAME']));
	}

	/**
	 * set url to $_GET
	 * @param string $url
	 * @return boolean
	 */
	private function setUrlToGet($url)
	{
		if (empty($url))
		{
			return false;
		}

		$url = str_replace($this->postfix, '', $url);
		$url = str_replace(array("?", "&", "="), $this->delimiter, $url);

		$arr = explode($this->delimiter, ltrim($url, "/"));

		if (count($arr) > 1)
		{
			$this->module = array_shift($arr);
			$this->action = array_shift($arr);
			$_GET['module'] = $this->module;
			$_GET['action'] = $this->action;
			$this->setValueToGet($arr);
		}
		return true;
	}

	/**
	 * set value to $_GET
	 * @param array $arr
	 * @param int $start
	 */
	private function setValueToGet($arr, $start = 0)
	{
		$i = $start;
		while (isset($arr[$i]) && isset($arr[$i + 1]))
		{
			$key = $arr[$i];
			if ($key !== '')
			{
				$arr[$i + 1] = str_replace('%FF', rawurlencode($this->delimiter), $arr[$i + 1]);
				$key = str_replace('%FF', $this->delimiter, $key);
				$_GET[$key] = rawurldecode($arr[$i + 1]);
			}
			$i = $i + 2;
		}
	}

	/**
	 * route from cli
	 */
	private function routeFromCli()
	{
		$arr = $_SERVER['argv'];
		array_shift($arr);
		$i = 0;
		while (isset($arr[$i]) && isset($arr[$i + 1]))
		{
			$key = rawurldecode(ltrim($arr[$i], '-'));
			$_GET[$key] = rawurldecode($arr[$i + 1]);
			$i = $i + 2;
		}

		if (isset($_GET['m']))
		{
			$this->module = $_GET['m'];
		}
		elseif (isset($_GET['module']))
		{
			$this->module = $_GET['module'];
		}

		if (isset($_GET['a']))
		{
			$this->action = $_GET['a'];
		}
		elseif (isset($_GET['action']))
		{
			$this->action = $_GET['action'];
		}
	}

	/**
	 * return module/action
	 * @return string
	 */
	public function __toString()
	{
		return $this->module.'/'.$this->action;
	}
}
