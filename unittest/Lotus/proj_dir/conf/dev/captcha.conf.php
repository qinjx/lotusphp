<?php
/**
 * ÑéÖ¤Âë
 */
$config['captcha.seed_file_root'] = "/tmp/xinghecms/captcha/seed/";
$config['captcha.allow_chars'] = "23456789abcdeghkmnpqsuvxyz";
$config['captcha.length'] = 4;
$config['captcha.image_engine'] = 'LtCaptchaImageEngine';
$config['captcha.image_engine_conf'] = array('blur' => false,
	'scale' => 2,
	'width' => 200,
	'height' => 80,
	'max_rotation' => 4,
	);
