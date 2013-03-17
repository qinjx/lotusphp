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
include $lotusHome . "runtime/MVC/TemplateView.php";

include $lotusHome . "runtime/Validator/Validator.php";
include $lotusHome . "runtime/Validator/ValidatorDtd.php";

/**
 * 加载Action类文件
 */
$appDir = "./simplest_tpl/";
include $appDir . "action/User-Signin.Action.php";
include $appDir . "action/Index-Index.Action.php";
include $appDir . "action/test-UsingComponent.Action.php";
include $appDir . "action/stock-Price.Component.php";
include $appDir . "action/test-UsingBlankLayout.Action.php";
include $appDir . "action/test-PassData.Action.php";
include $appDir . "action/test-UsingTitle.Action.php";
/**
 * 实例化
 */
$dispatcher = LtObjectUtil::singleton('LtDispatcher');
$dispatcher->viewDir = "./simplest_tpl/view/";
/**
 * 保存模板编译后的文件目录,
 * 如果不指定,默认在view同级目录生成LtTemplateView目录
 */
$dispatcher->viewTplDir = "/tmp/Lotus/templateView/";

$module = isset($_GET['module']) ? $_GET['module'] : 'Index';
$action = isset($_GET['action']) ? $_GET['action'] : 'Index';
$dispatcher->dispatchAction($module, $action);
