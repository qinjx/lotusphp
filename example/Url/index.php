﻿<?php
$lotusHome = substr(__FILE__, 0, strpos(__FILE__, "example")) . '/';
include $lotusHome . "runtime/Config.php";
include $lotusHome . "runtime/Store.php";
include $lotusHome . "runtime/StoreMemory.php";
include $lotusHome . "runtime/Url/Url.php";

$config['router.routing_table'] = array('pattern' => ":module-:action-ak47-*",
	'default' => array('module' => 'default', 'action' => 'index'),
	'reqs' => array('module' => '[a-zA-Z0-9\.\-_]+', 'action' => '[a-zA-Z0-9\.\-_]+'),
	'varprefix' => ':',
	'delimiter' => '-',
	'postfix' => '.html',
	'protocol' => 'STANDARD', // REWRITE STANDARD PATH_INFO
	);

// 初始化LtUrl
$url = new LtUrl;
$url->configHandle->addConfig($config);
$url->init();
$href = $url->generate('news', 'list', array('catid' => 4, 'page' => 10));

echo $href;
