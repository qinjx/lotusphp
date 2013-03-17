<?php
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . "common.inc.php";

$operation = $_REQUEST["operation"];

/**
 * Lotus组件初始化三步曲
 */ 
// 1. 实例化
$cookie = new LtCookie; 
// 2. 设置属性
$cookie->configHandle->addConfig(array("cookie.secret_key" => "dsds@#$%^&*(werewt"));
// 3. 调init()方法
$cookie->init();

/**
 * 初始化完毕，测试其效果
 */
switch ($operation)
{
	case "set":
		foreach ($_REQUEST["cookies"] as $cookieName => $cookieValue)
		{
			$cookie->setCookie($cookieName, $cookieValue, time() + 3600);
		}
		break;
	case "get":
		echo serialize($cookie->getCookie($_REQUEST["cookie_name"]));
		break;
	case "del":
		$cookie->delCookie($_REQUEST["cookie_name"]);
		break;
}
