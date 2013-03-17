<?php
/**
 * 加载MVC类文件
加载的类很多，且需要注意先后顺序，推荐使用LtAutoloader自动加载
 */
$lotusHome = substr(__FILE__, 0, strpos(__FILE__, "example")) . '/';

include $lotusHome . "runtime/Config.php";
include $lotusHome . "runtime/Store.php";
include $lotusHome . "runtime/StoreMemory.php";
include $lotusHome . "runtime/ObjectUtil/ObjectUtil.php";

include $lotusHome . "runtime/MVC/Dispatcher.php";
include $lotusHome . "runtime/MVC/Action.php";
include $lotusHome . "runtime/MVC/Component.php";
include $lotusHome . "runtime/MVC/Context.php";
include $lotusHome . "runtime/MVC/View.php";

include $lotusHome . "runtime/Validator/Validator.php";
include $lotusHome . "runtime/Validator/ValidatorDtd.php"; 
// 配置文件
$config['validator.error_messages'] = array('ban' => '%s contain banned words',
	'mask' => '%s does not match the given format',
	'max_length' => '%s is longer than %s',
	'min_length' => '%s is shorter than %s',
	'max_value' => '%s is bigger than %s',
	'min_value' => '%s is smaller than %s',
	'max_selected' => '%s is too much',
	'min_selected' => '%s is too few',
	'required' => '%s is empty',
	'equal_to' => '%s is not equal to %s',
	);

$configHandle = new LtConfig;
$configHandle->addConfig($config);

/**
 * 加载Action类文件
 */
$appDir = "./simplest_app/";
include $appDir . "action/User-Signin.Action.php";

/**
 * 实例化
 */
$dispatcher = LtObjectUtil::singleton('LtDispatcher');

$dispatcher->configHandle = $configHandle;

$dispatcher->viewDir = "./simplest_app/view/";
$dispatcher->dispatchAction("User", "Signin");
