<?php
/**
 * 读取项目共享配置
 */
$projHome = substr(__FILE__, 0, strpos(__FILE__, "app"));
$config = include($projHome . "/conf/conf.php");
/**
 * 读取standard配置,
 * 并覆盖共享配置的部分配置
 */
foreach(glob(dirname(__FILE__) . '/standard/*.php') as $confFile)
{
	if (__FILE__ != $confFile)
	{
		include($confFile);
	}
}

return $config;