<?php
/**
 * 加载Captcha类文件
 */
$lotusHome = substr(__FILE__, 0, strpos(__FILE__, "example")) . '/';
include $lotusHome . "runtime/Config.php";
include $lotusHome . "runtime/Store.php";
include $lotusHome . "runtime/StoreMemory.php";
include $lotusHome . "runtime/StoreFile.php";
include $lotusHome . "runtime/Captcha/Captcha.php";
include $lotusHome . "runtime/Captcha/CaptchaImageEngine.php";
/**
 * 加载Captcha类文件
 */

/**
 * 开始使用Captcha
 */
$captcha = new LtCaptcha;
$config['captcha.seed_file_root'] = "/tmp/Lotus/captcha/seed/";
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
