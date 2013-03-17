<?php
$config = array();

foreach(glob(dirname(__FILE__) . '/standard/*.php') as $confFile)
{
	if (__FILE__ != $confFile)
	{
		include($confFile);
	}
}

return $config;
