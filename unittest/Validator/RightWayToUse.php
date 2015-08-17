<?php
/**
 * 本测试文档演示了LtValidator的正确使用方法 
 * 按本文档操作一定会得到正确的结果
 */
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . "common.inc.php";
class RightWayToUseValidator extends PHPUnit_Framework_TestCase
{
	public function testMostUsedWay()
	{
		$username = '123fuck 4567890';
		$password = '123fuck 4567890';

		$dtds['username'] = new LtValidatorDtd($_POST['username'],"username",
			array("max_length" => 8,
				"mask" => "/^[a-z0-9]+$/i",
				"ban" => "/fuck/",
				),
			array(
				// "max_length" 使用默认的错误消息
				"mask" => "用户名只能由数字或字组成",
				"ban" => "用户名不能包含脏话"
				)
			);

		$dtds['password'] = new LtValidatorDtd($_POST['password'],"password",
			array("max_length" => 8,
				"mask" => "/^[a-z0-9]+$/i",
				"ban" => "/fuck/",
				),
			array("max_length" => "密码最长8位",
				"mask" => "密码只能由数字或字组成", 
				// "ban" => "密码不能包含脏话"
				)
			); 
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

		$validator = new LtValidator;

		$validator->configHandle = $configHandle;

		$validator->init();

		$dtd = $dtds['username'];
		foreach ($dtd->rules as $ruleKey => $ruleValue)
		{
			if ($ruleValue instanceof LtConfigExpression)
			{
				eval('$_ruleValue = ' . $ruleValue->__toString());
				$dtd->rules[$ruleKey] = $ruleValue;
			}
		}
		$error_messages = $validator->validate($dtd);
		$this->assertEquals(
			array('max_length' => 'username is longer than 8',
				'mask' => '用户名只能由数字或字组成',
				'ban' => '用户名不能包含脏话'
				), $error_messages);

		$dtd = $dtds['password'];
		foreach ($dtd->rules as $ruleKey => $ruleValue)
		{
			if ($ruleValue instanceof LtConfigExpression)
			{
				eval('$_ruleValue = ' . $ruleValue->__toString());
				$dtd->rules[$ruleKey] = $ruleValue;
			}
		}
		$error_messages = $validator->validate($dtd);
		$this->assertEquals(
			array('max_length' => '密码最长8位',
				'mask' => '密码只能由数字或字组成',
				'ban' => 'password contain banned words'
				), $error_messages);
	}

	protected function setUp()
	{
	}

	protected function tearDown()
	{
	}
}
