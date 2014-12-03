<?php
/**
 * The Context class
 * @author Jianxiang Qin <TalkativeDoggy@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause New BSD License
 * @version svn:$Id: Context.php 964 2012-08-27 04:02:32Z zhao5908@gmail.com $
 */

/**
 * The Context class
 * @author Jianxiang Qin <TalkativeDoggy@gmail.com>
 * @category runtime
 * @package   Lotusphp\MVC
 */
class LtContext
{
	/** @var array The uri property */
	public $uri;

	/**
	 * construct
	 */
	public function __construct()
	{

	}

	/**
	 * return the client input in $_SERVER['argv']
	 * 
	 * @param integer $offset 
	 * @return string 
	 */
	public function argv($offset)
	{
		return isset($_SERVER['argv']) && isset($_SERVER['argv'][$offset]) ? $_SERVER['argv'][$offset] : null;
	}

	/**
	 * return the client input in $_FILES
	 * 
	 * @param string $name 
	 * @return array 
	 */
	public function file($name)
	{
		return isset($_FILES[$name]) ? $_FILES[$name] : null;
	}

	/**
	 * return the client input in $_GET
	 * 
	 * @param string $name 
	 * @return string 
	 */
	public function get($name)
	{
		return isset($_GET[$name]) ? $_GET[$name] : null;
	}

	/**
	 * return the client input in $_POST
	 * 
	 * @param string $name 
	 * @return string 
	 */
	public function post($name)
	{
		return isset($_POST[$name]) ? $_POST[$name] : null;
	}

	/**
	 * return the client input in $_REQUEST
	 * 
	 * @param string $name 
	 * @return string 
	 */
	public function request($name)
	{
		return isset($_REQUEST[$name]) ? $_REQUEST[$name] : null;
	}

	/**
	 * return the client input in $_SERVER
	 * 
	 * @param string $name 
	 * @return string 
	 */
	public function server($name)
	{
		if ('REMOTE_ADDR' == $name)
		{
			if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
			{
				$clientIp = $_SERVER['HTTP_X_FORWARDED_FOR'];
			}
			else
			{
				$clientIp = $_SERVER[$name];
			}
			return $clientIp;
		}
		else
		{
			return isset($_SERVER[$name]) ? $_SERVER[$name] : null;
		}
	}
}
