<?php
/**
 * 本测试文档演示了Router的正确使用方法 
 * 按本文档操作一定会得到正确的结果
 */
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . "common.inc.php";
class RightWayToUseRouter extends PHPUnit_Framework_TestCase
{
	public function testMostUsedWay()
	{ 
		// 模拟浏览器访问
		$_SERVER['SERVER_PROTOCOL'] = 'HTTP/1.1';
		$_SERVER['REQUEST_URI'] = '/index.php?module=default0&action=index0&id=123456&page=12&q-%2Fkey=%E7%A9%BA%20-%2F%E6%A0%BC'; 
		$_SERVER['SCRIPT_NAME'] = '/index.php';
		$_GET = array (
						'module' => 'default0',
						'action' => 'index0',
						'id' => '123456',
						'page' => '12',
						'q-/key' => '空 -/格',
				);
		
		// 默认的module和action的名字
		$config['router.routing_table']['default'] = array('module' => 'default', 'action' => 'index');
		// URL中变量的分隔符号
		$config['router.routing_table']['delimiter'] = '-';
		// 后缀，常用来将URL模拟成单个文件
		$config['router.routing_table']['postfix'] = '.html';
		// REWRITE STANDARD PATH_INFO 三种模式，不分大小写
		$config['router.routing_table']['protocol'] = 'STANDARD';
		$configHandle = new LtConfig();
		$configHandle->addConfig($config);
		
		/**
		 * LtRouter 使用方法
		 */
		$router = new LtRouter();
		$router->configHandle = $configHandle;
		$router->init();

		$this->assertEquals('default0', $router->module);
		$this->assertEquals('index0', $router->action);
	}

	public function testRewrite()
	{
		// 模拟浏览器访问
		$_SERVER['SERVER_PROTOCOL'] = 'HTTP/1.1';
		$_SERVER['REQUEST_URI'] = '/default1-index1-id-123456-page-12-q%FF/key-%E7%A9%BA%20%FF%2F%E6%A0%BC.html';
		$_SERVER['SCRIPT_NAME'] = '/index.php';
		
		$config['router.routing_table']['default'] = array('module' => 'default', 'action' => 'index');
		$config['router.routing_table']['protocol'] = 'REWRITE';
		$configHandle = new LtConfig();
		$configHandle->addConfig($config);

		$router = new LtRouter();
		$router->configHandle = $configHandle;
		$router->init();

		$this->assertEquals('default1', $router->module);
		$this->assertEquals('index1', $router->action);
		$this->assertEquals(
				array (
						'module' => 'default1',
						'action' => 'index1',
						'id' => '123456',
						'page' => '12',
						'q-/key' => '空 -/格',
				),
				$_GET
		);
	}

	public function testPathinfo()
	{
		$_SERVER['SERVER_PROTOCOL'] = 'HTTP/1.1';
		$_SERVER["REQUEST_URI"] = '/index.php/default2/index2/id/123456/page/12/q-%FFkey/%E7%A9%BA%20-%FF%E6%A0%BC.html';
		$_SERVER['SCRIPT_NAME'] = '/index.php';
		
		$config['router.routing_table']['default'] = array('module' => 'default', 'action' => 'index');
		$config['router.routing_table']['protocol'] = 'PATH_INFO';
		$configHandle = new LtConfig();
		$configHandle->addConfig($config);

		$router = new LtRouter();
		$router->configHandle = $configHandle;
		$router->init();

		$this->assertEquals('default2', $router->module);
		$this->assertEquals('index2', $router->action);
		$this->assertEquals(
				array (
						'module' => 'default2',
						'action' => 'index2',
						'id' => '123456',
						'page' => '12',
						'q-/key' => '空 -/格',
				),
				$_GET
		);
	}

	/**
	 * 命令行模式
	 */
	public function testCLI()
	{
		unset($_SERVER['SERVER_PROTOCOL']);
		$_SERVER['argv'] = array('index.php', '--module', 'hello', '--action', 'world',);
		$router = new LtRouter;
		$router->init();
		$this->assertEquals('hello', $router->module);
		$this->assertEquals('world', $router->action);
		$this->assertEquals(
				array (
						'module' => 'hello',
						'action' => 'world',
				),
				$_GET
		);
	}
	public function testCLI2()
	{
		unset($_SERVER['SERVER_PROTOCOL']);
		$_SERVER['argv'] = array('index.php', '-m', 'hello', '-a', 'world',);
		$router = new LtRouter;
		$router->init();
		$this->assertEquals('hello', $router->module);
		$this->assertEquals('world', $router->action);
		$this->assertEquals(
				array (
						'm' => 'hello',
						'a' => 'world',
				),
				$_GET
		);
	}

	//  @todo 
	// 单元测试似乎有bug
	// 只要使用构造函数同时使用@dataProvider
	// 就会 Missing argument 1 错误
	// public function __construct()
	// {
	// parent::__construct();
	// $this->config['router.routing_table']['default'] = array('module' => 'default', 'action' => 'index');
	// $this->config['router.routing_table']['protocol'] = 'REWRITE';
	// }
	
	protected function setUp()
	{
		$_GET = array();
		$_SERVER['SERVER_PROTOCOL'] = '';
		$_SERVER['REQUEST_URI'] = '';
		$_SERVER['SCRIPT_NAME'] = '';
	}
	
	protected function tearDown()
	{
	}
}
