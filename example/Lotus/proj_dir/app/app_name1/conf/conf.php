<?php
/**
 * 读取项目共享配置
 */
$projHome = substr(__FILE__, 0, strpos(__FILE__, "app"));
$config = include($projHome . "/conf/conf.php");
include(dirname(__FILE__) . DIRECTORY_SEPARATOR . "shared_conf.php");

return $config;