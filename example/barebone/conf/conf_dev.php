<?php
/**
 * 开发模式下先读取standard配置,
 * 然后读取dev配置,并覆盖standard的部分配置
 */
include(dirname(__FILE__) .'/conf.php');

foreach(glob(dirname(__FILE__) . '/dev/*.php') as $confFile)
{
	if (__FILE__ != $confFile)
	{
		include($confFile);
	}
}

return $config;
