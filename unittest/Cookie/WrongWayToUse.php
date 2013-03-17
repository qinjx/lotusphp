<?php
/**
 * 本测试文档演示了LtCookie的错误使用方法 
 * 不要按本文档描述的方式使用LtCookie
 */
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . "common.inc.php";
class WrongWayToUseCookie extends PHPUnit_Framework_TestCase
{
	/**
	 * 
	 * 不设置密钥就开始使用LtCookie
	 * @expectedException PHPUnit_Framework_Error
	 */
	public function testNoSecretKeySet()
	{
		$cookie = new LtCookie;
		//不设置密钥 或者 密钥为空
		$cookie->configHandle->addConfig(array("cookie.secret_key" => ""));
		$cookie->init();
	}

	protected function setUp()
	{
	}
	protected function tearDown()
	{
	}
}
