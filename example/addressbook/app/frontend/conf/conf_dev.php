<?php
/**
 * 读取项目共享配置
 */
$projHome = substr(__FILE__, 0, strpos(__FILE__, "app"));
$config = include($projHome . "/conf/conf_dev.php");

/**
 * 开发模式下先读取standard配置,
 * 然后读取dev配置,并覆盖standard的部分配置
 */
include(dirname(__FILE__) . '/conf.php');

/**
 * 读取dev配置
 */
foreach(glob(dirname(__FILE__) . '/dev/*.php') as $confFile)
{
	if (__FILE__ != $confFile)
	{
		include($confFile);
	}
}

return $config;
