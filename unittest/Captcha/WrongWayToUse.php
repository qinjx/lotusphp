<?php
/**
 * 本测试文档演示了LtCaptcha的错误使用方法 
 * 不要按本文档描述的方式使用LtCaptcha
 */
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . "common.inc.php";
class WrongWayToUseCaptcha extends PHPUnit_Framework_TestCase
{
	/**
	 * 调用getImageResource()和verify()接口不带seed参数
	 * @expectedException PHPUnit_Framework_Error
	 */
	public function testBlankSeed()
	{
		$captcha = new LtCaptcha;
		$config['captcha.allow_chars'] = "23456789abcdeghkmnpqsuvxyz";
		$config['captcha.length'] = 4;
		$config['captcha.image_engine'] = 'LtCaptchaImageEngine';
		$config['captcha.image_engine_conf'] = array('blur' => false,
			'scale' => 2,
			'width' => 200,
			'height' => 80,
			'max_rotation' => 4,
			);
		$captcha->configHandle->addConfig($config);
		$captcha->init();
		$im = $captcha->getImageResource("");
	}
	protected function setUp()
	{
	}
	protected function tearDown()
	{
	}
}
