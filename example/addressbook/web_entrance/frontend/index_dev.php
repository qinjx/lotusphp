<?php
$lotusHome = substr(dirname(__FILE__), 0, strpos(__FILE__, "example"));
include $lotusHome . 'runtime/Lotus.php';

$lotus = new Lotus();
$lotus->option['proj_dir'] = dirname(dirname(dirname(__FILE__)));
$lotus->option['app_name'] = 'frontend';
$lotus->devMode = true;
$lotus->init();

// xdebug info
if (function_exists('xdebug_time_index') && function_exists('xdebug_peak_memory_usage'))
{
	echo "\n<script type=\"text/javascript\">\n";
	echo 'document.getElementById("debug_info").innerHTML = "';
	echo xdebug_time_index();
	echo ' - ';
	echo format_size(xdebug_peak_memory_usage());
	echo "\";\n</script>\n";
}
