<?php
/**
 * 本测试文档演示了Lotus的正确使用方法
 */
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . "common.inc.php";
class RightWayToUseLotus extends PHPUnit_Framework_TestCase
{
	/**
	 * 最常用的使用方式
	 */
	public function testMostUsedWay()
	{
		/**
		 * 初始化Lotus类
		 */
		$lotus = new Lotus();
		/**
		 * 项目目录, 按照约定的目录结构,自动加载共享配置文件
		 */
		$lotus->option['proj_dir'] = dirname(__FILE__) . '/proj_dir/';
		/**
		 * 应用名称
		 */
		$lotus->option['app_name'] = 'app_name1';
		/**
		 * 可选
		 */
		$lotus->defaultStoreDir = '/tmp';
		/**
		 * MVC模式 默认 true
		 */
		$lotus->mvcMode = true;
		/**
		 * 开发模式, 默认 true
		 */
		$lotus->devMode = true;
		/**
		 * run
		 */
		$lotus->init();

		/**
		 * class_exists默认调用自动加载
		 */
		$this->asserttrue(class_exists("LtCaptcha"));
	}

	protected function setUp()
	{
		$_SERVER['SERVER_PROTOCOL'] = 'HTTP/1.1'; 
		// $_GET['module'] = 'Default';
		// $_GET['action'] = 'Index';
		$_SERVER['PATH_INFO'] = '/Default/Index';
	}

	protected function tearDown()
	{
	}
}
