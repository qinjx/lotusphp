<?php
/**
 * 本测试文档演示了Url的正确使用方法 
 * 按本文档操作一定会得到正确的结果
 */
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . "common.inc.php";
class RightWayToUseUrl extends PHPUnit_Framework_TestCase
{

	public function testMostUsedWay()
	{
		// 默认的module和action的名字
		$config['router.routing_table']['default'] = array('module' => 'default', 'action' => 'index');
		// URL中变量的分隔符号
		$config['router.routing_table']['delimiter'] = '-';
		// 后缀，常用来将URL模拟成单个文件
		$config['router.routing_table']['postfix'] = '.html';
		// REWRITE STANDARD PATH_INFO 三种模式，不分大小写
		$config['router.routing_table']['protocol'] = 'standard';
		$configHandle = new LtConfig();
		$configHandle->addConfig($config);

		// 初始化LtUrl
		$url = new LtUrl;
		$url->configHandle = $configHandle;
		$url->init(); 
		// 初始化结束
		// 测试生成超链接
		$href = $url->generate('news', 'list', array('catid' => 4, 'page' => 10));
		$this->assertEquals('/index.php?module=news&action=list&catid=4&page=10', $href);
	}
	
	public function testOther()
	{
		$url = new LtUrl;
		$url->init();
		$params = array(
				'id' => 123456,
				'page' => '12',
				//'void' => null,
				'q-/key' => '空 -/格',
				//'empty' => '',
		);
		$baseUrl = 'http://localhost';
		$link = $url->generate('goods', 'detail', $params, $baseUrl, 'rewrite');
		$this->assertEquals('http://localhost/goods-detail-id-123456-page-12-q%FF/key-%E7%A9%BA%20%FF%2F%E6%A0%BC.html', $link);
	
		$baseUrl = 'http://127.0.0.1';
		$link = $url->generate('goods', 'detail', $params, $baseUrl, 'rewrite');
		$this->assertEquals('http://127.0.0.1/goods-detail-id-123456-page-12-q%FF/key-%E7%A9%BA%20%FF%2F%E6%A0%BC.html', $link);
		
		$link = $url->generate('goods', 'detail', $params, $baseUrl, 'path_info');
		$this->assertEquals('http://127.0.0.1/index.php/goods/detail/id/123456/page/12/q-%FFkey/%E7%A9%BA%20-%FF%E6%A0%BC.html', $link);
		
		$link = $url->generate('goods', 'detail', $params, $baseUrl, 'standard');
		$this->assertEquals('http://127.0.0.1/index.php?module=goods&action=detail&id=123456&page=12&q-%2Fkey=%E7%A9%BA%20-%2F%E6%A0%BC', $link);
		
		$link2 = $url->getLink('goods', 'detail', $params, $baseUrl);
		$this->assertEquals($link, $link2);
	}

	public function testPathinfo()
	{
		$config['router.routing_table']['protocol'] = 'PATH_INFO';
		$configHandle = new LtConfig();
		$configHandle->addConfig($config);
	
		$url = new LtUrl;
		$url->configHandle = $configHandle;
		$url->init();
		
		$params = array(
				'id' => 123456,
				'page' => '12',
				//'void' => null,
				'q-/key' => '空 -/格',
				//'empty' => '',
		);
		$url->baseUrl = 'http://localhost';
		$link = $url->generate('goods', 'detail', $params);
		$this->assertEquals('http://localhost/index.php/goods/detail/id/123456/page/12/q-%FFkey/%E7%A9%BA%20-%FF%E6%A0%BC.html', $link);

		$url->baseUrl = 'http://127.0.0.1';
		$link = $url->generate('goods', 'detail', $params);
		$this->assertEquals('http://127.0.0.1/index.php/goods/detail/id/123456/page/12/q-%FFkey/%E7%A9%BA%20-%FF%E6%A0%BC.html', $link);
		
	}
	
	public function testRewrite()
	{
		$config['router.routing_table']['protocol'] = 'REWRITE';
		$configHandle = new LtConfig();
		$configHandle->addConfig($config);
	
		$url = new LtUrl;
		$url->configHandle = $configHandle;
		$url->init();
	
		$params = array(
				'id' => 123456,
				'page' => '12',
				//'void' => null,
				'q-/key' => '空 -/格',
				//'empty' => '',
		);
		$url->baseUrl = 'http://localhost';
		$link = $url->generate('goods', 'detail', $params);
		$this->assertEquals('http://localhost/goods-detail-id-123456-page-12-q%FF/key-%E7%A9%BA%20%FF%2F%E6%A0%BC.html', $link);
	
		$url->baseUrl = 'http://127.0.0.1';
		$link = $url->generate('goods', 'detail', $params);
		$this->assertEquals('http://127.0.0.1/goods-detail-id-123456-page-12-q%FF/key-%E7%A9%BA%20%FF%2F%E6%A0%BC.html', $link);
	
	}

	protected function setUp()
	{
		$_SERVER['SCRIPT_NAME'] = '/index.php';
		$_SERVER['PHP_SELF'] = '/index.php';
	}
	protected function tearDown()
	{
	}
}
