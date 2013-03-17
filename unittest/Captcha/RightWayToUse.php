<?php
/**
 * 本测试文档演示了LtCaptcha的正确使用方法 
 * 按本文档操作一定会得到正确的结果
 */
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . "common.inc.php";
class RightWayToUseCaptcha extends PHPUnit_Framework_TestCase
{
	/**
	 * 最常用的使用方式（推荐） 
	 * -------------------------------------------------------------------
	 * LtCaptcha要求： 
	 *    # 调用getImageResource()时传入唯一的seed，不能是常量
	 * 
	 * -------------------------------------------------------------------
	 * LtCaptcha建议（不强求）：
	 *    # 使用md5(uniqid())得到随机不冲突的seed
	 * 
	 * 本测试用例期望效果：
	 * 无法对图片进行单元测试，实际使用方法和效果参见example/Captcha/simplest.php
	 */
	public function testMostUsedWay()
	{
		/**
		 * 使用方法
		 */
		$captcha = new LtCaptcha;
		/**
		 * lphabet without similar symbols (o=0, 1=l, i=j, t=f)
		 */
		$config['captcha.allow_chars'] = "23456789abcdeghkmnpqsuvxyz";
		$config['captcha.length'] = 4;

		$config['captcha.image_engine'] = 'LtCaptchaImageEngine';
		/**
		 * Blur :: effect for better image quality (but slower image processing).
		 * Better image results with scale=3
		 * --------
		 * scale :: Internal image size factor (for better image quality)
		 * 1: low, 2: medium, 3: high
		 * ------
		 * max_rotation :: letter rotation clockwise
		 */
		$config['captcha.image_engine_conf'] = array('blur' => false,
			'scale' => 2,
			'width' => 200,
			'height' => 80,
			'max_rotation' => 4,
			);

		/**
		 * 生产环境中配置已经通过LtCache缓存
		 */
		$captcha->configHandle->addConfig($config);

		$captcha->init();
		/**
		 * 初始化完毕，测试其效果
		 */
		$this->assertTrue(is_resource($captcha->getImageResource(md5(uniqid()))));
	}

	/**
	 * 测试verify接口是否能正常工作
	 */
	public function testVerify()
	{
		$cp = new LtCaptcha;
		$config['captcha.allow_chars'] = "23456789abcdeghkmnpqsuvxyz";
		$config['captcha.length'] = 4;
		$config['captcha.image_engine'] = 'LtCaptchaImageEngine';
		$config['captcha.image_engine_conf'] = array('blur' => false,
			'scale' => 2,
			'width' => 200,
			'height' => 80,
			'max_rotation' => 4,
			);
		$cp->configHandle->addConfig($config);
		$cp->init();
		$seed = md5(uniqid());
		$cp->getImageResource($seed);
		$word = $cp->storeHandle->get($seed);
		$this->assertTrue($cp->verify($seed, $word));
	}
	protected function setUp()
	{
	}
	protected function tearDown()
	{
	}
}
