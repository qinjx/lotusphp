<?php
/**
 * The View class
 * @author Jianxiang Qin <TalkativeDoggy@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause New BSD License
 * @version svn:$Id: ObjectUtil.php 964 2012-08-27 04:02:32Z zhao5908@gmail.com $
 */

/**
 * The View class
 * @author Jianxiang Qin <TalkativeDoggy@gmail.com>
 * @category runtime
 * @package   Lotusphp\ObjectUtil
 */
class LtObjectUtil
{
	/** @var object some class instance */
	static $instances;
	/**
	 * singleton
	 * @param string $className
	 * @param boolean $autoInited Whether init() method call
	 * @return boolean | object
	 */
	static public function singleton($className, $autoInited = true)
	{
		if (empty($className))
		{
			trigger_error('empty class name');
			return false;
		}
		$key = strtolower($className);
		if (isset(self::$instances[$key]))
		{
			return self::$instances[$key];
		}
		else if (class_exists($className))
		{
			$newInstance = new $className;
			if ($autoInited && method_exists($newInstance, 'init'))
			{
				$newInstance->init();
			}
			self::$instances[$key] = $newInstance;
			return $newInstance;
		}
		else
		{
			return false;
		}
	}
}
