<?php
$lotusHome = substr(dirname(__FILE__), 0, strpos(__FILE__, "example"));
include $lotusHome . 'runtime/Lotus.php';

$lotus = new Lotus();

$lotus->devMode = false; // 关闭开发模式

$lotus->option['proj_dir'] = dirname(__FILE__) . '/proj_dir/';
$lotus->option['app_name'] = 'app_name1';
$lotus->init();

/**
 * 使用xdebug测试性能
 */
if (function_exists('xdebug_time_index') && function_exists('xdebug_peak_memory_usage'))
{
	echo "\n<script type=\"text/javascript\">\n";
	echo 'document.getElementById("debug_info").innerHTML = "';
	echo xdebug_time_index();
	echo ' - ';
	echo format_size(xdebug_peak_memory_usage());
	echo "\";\n</script>\n";
}

function format_size($size)
{
	if ($size >= 1073741824)
	{
		$size = round($size / 1073741824, 2) . ' GB';
	}
	else if ($size >= 1048576)
	{
		$size = round($size / 1048576, 2) . ' MB';
	}
	else if ($size >= 1024)
	{
		$size = round($size / 1024, 2) . ' KB';
	}
	else
	{
		$size = round($size, 2) . ' Bytes';
	}
	return $size;
}
