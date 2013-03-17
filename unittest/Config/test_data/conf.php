<?php
/**
 * 这个数组变量叫什么都是无所谓的，只要在末尾把它return即可
 */
$config = array();

/**
 * 自动扫描conf目录下的php文件，将之包含进来，但不支持子目录
 * 这段代码等价于：
 * include("conf/db.conf.php");
 * include("conf/validator.conf.php");
 */
foreach(glob(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'conf' . DIRECTORY_SEPARATOR . '*.php') as $confFile)
{
	if (__FILE__ != $confFile)
	{
		include($confFile);
	}
}

/**
 * 一定不要忘了这个return语句
 * 如果不return，include(conf.php)的时候收到的返回值是整数1
 * 加了return，include(conf.php)收到的返回值才是数组，lotusphp需要的返回值是一个数组
 */
return $config;