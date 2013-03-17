<?php
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