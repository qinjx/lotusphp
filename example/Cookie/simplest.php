<?php
ob_start();

/**
 * 加载Cookie类文件
 */
$lotusHome = substr(__FILE__, 0, strpos(__FILE__, "example"));
include $lotusHome . "/runtime/Cookie/Cookie.php";
include $lotusHome . "/runtime/Config.php";
include $lotusHome . "/runtime/Store.php";
include $lotusHome . "/runtime/StoreMemory.php";

/**
 * 开始使用Cookie
 * php.ini需要修改为output_buffering = On
 */

$cookie = new LtCookie;

$cookie->setCookie('newproj', 'hello', time() + 3600);
// 值为数组
$cookie->setCookie('test', array('a', 'b', 'c', 'd'), time() + 3600);
if ($_COOKIE)
{
	print_r($cookie->getCookie('newproj'));
	print_r($cookie->getCookie('test'));
	$cookie->delCookie('newproj');
	$cookie->delCookie('test');
}
else
{
	echo "set cookie ....";
}
