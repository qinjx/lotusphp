<?php

/**
 * The Url class
 * @author Yi Zhao <zhao5908@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause New BSD License
 * @version svn:$Id: Url.php 964 2012-08-27 04:02:32Z zhao5908@gmail.com $
 */

/**
 * The Url class
 * @author Yi Zhao <zhao5908@gmail.com>
 * @category runtime
 * @package   Lotusphp\Url
 */
class LtUrl
{
	/** @var LtConfig config handle */
	public $configHandle;
	
	/** @var string for example $baseUrl=http://www.example.com */
	public $baseUrl = '';
	
	/** @var boolean whether contains relative path,default true */
	public $withPath = true;
	
	/** @var array default module action value */
	private $default = array('module' => 'default', 'action' => 'index');
	
	/** @var string delimiter url */
	private $delimiter = '-';
	
	/** @var string postfix url */
	private $postfix = '.html';
	
	/** @var string for example REWRITE PATH_INFO STANDARD */
	private $protocol = 'STANDARD';

	/**
	 * construct
	 */
	public function __construct()
	{
		if (!$this->configHandle instanceof LtConfig)
		{
			if (class_exists("LtObjectUtil", false))
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
	}

	/**
	 * get STANDARD url
	 * @param string $module
	 * @param string $action
	 * @param array $args
	 * @param string $baseUrl
	 * @return string
	 */
	public function getLink($module, $action, $args = array(), $baseUrl = null)
	{
		return $this->generate($module, $action, $args, $baseUrl, 'STANDARD');
	}

	/**
	 * get REWRITE PATH_INFO STANDARD url
	 * @param string $module
	 * @param string $action
	 * @param array $args
	 * @param string $baseUrl
	 * @param string $protocol
	 * @return string
	 */
	public function generate($module, $action, $args = array(), $baseUrl = null, $protocol = null)
	{
		if (null === $baseUrl)
		{
            $baseUrl = $this->baseUrl;
		}
		$protocol = $protocol ? strtoupper($protocol) : $this->protocol;
		switch ($protocol)
		{
			case 'REWRITE':
				$url = $this->withPath ? rtrim(pathinfo($_SERVER['SCRIPT_NAME'], PATHINFO_DIRNAME), '\\/') . '/' : '';
				$url .= $module . $this->delimiter . $action;
				$url .= $this->build_url($args);
				break;
			case 'PATH_INFO':
				$url = $this->withPath ? $_SERVER['SCRIPT_NAME'] . '/' : '';
				$old_delimiter = $this->delimiter;
				$this->delimiter = '/';
				$url .= $module . '/' . $action;
				$url .= $this->build_url($args);
				$this->delimiter = $old_delimiter;
				break;
			default :
				$url = $this->withPath ? $_SERVER['PHP_SELF'] . '?' : '?';
				$old_delimiter = $this->delimiter;
				$this->delimiter = '';
				$old_postfix = $this->postfix;
				$this->postfix = '';
				if (!is_array($args))
				{
					$args = array();
				}
				$arr = array_merge(array('module' => $module, 'action' => $action), $args);
				$url .= $this->standard_build_url($arr);
				$this->delimiter = $old_delimiter;
				$this->postfix = $old_postfix;
				break;
		}
		return $baseUrl . $url;
	}

    public function getRootDomain($hostname) {
        $tokens = explode(".", $hostname);
        $tokenLen = count($tokens);
        if (2 < $tokenLen) {

        } else {
            return null;
        }
    }

    public function getAbsoluteUrl($currentUrl, $href) {

    }

	/**
	 * standard build url
	 * @param array $arr
	 * @return string
	 */
	private function standard_build_url($arr)
	{
		$url = '';
		foreach ($arr AS $key => $value)
		{
			$url .= rawurlencode($key) . '=' . rawurlencode($value) . '&';
		}
		return rtrim($url, '&');
	}

	/**
	 * build url
	 * @param array $arr
	 * @return string
	 */
	private function build_url($arr)
	{
		$url = '';
		if (!empty($arr) && is_array($arr))
		{
			foreach ($arr AS $key => $value)
			{
				$key = str_replace($this->delimiter, '%FF', $key);
				$value = rawurlencode($value);
				$value = str_replace(rawurlencode($this->delimiter), '%FF', $value);
				$url .= $key . $this->delimiter . $value . $this->delimiter;
			}
			$url = $this->delimiter . substr($url, 0, -1);
		}
		return $url . $this->postfix;
	}

}
