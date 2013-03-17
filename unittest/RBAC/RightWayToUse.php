<?php
/**
 * 本测试文档演示了RBAC的正确使用方法 
 * 按本文档操作一定会得到正确的结果
 */
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . "common.inc.php";
class RightWayToUseRBAC extends PHPUnit_Framework_TestCase
{
	public function testMostUsedWay()
	{ 
		// 角色 可以是多个
		$roles = array('Administrators', 'Users'); 
		$roles = array_merge(array("*"), $roles);


		// 访问控制列表 deny优先
		$acl['allow']['*'][] = 'Index/Index';
		$acl['deny']['*'][] = '';

		$acl['allow']['Administrators'][] = 'admin/*';
		$acl['allow']['Administrators'][] = 'User/*';

		$acl['allow']['Users'][] = 'User/View';
		$acl['allow']['Users'][] = 'User/Signin';
		$acl['allow']['Users'][] = 'User/DoSignin';

		$acl['deny']['Users'][] = 'User/AddUser'; 

		$configHandle = new LtConfig;
		$configHandle->addConfig(array('rbac.acl'=>$acl));


		$rbac = new LtRbac();
		$rbac->configHandle = $configHandle;
		$rbac->init();

		$this->assertTrue($rbac->checkAcl($roles, 'admin/test'));
		$this->assertFalse($rbac->checkAcl($roles, 'User/AddUser'));
	}

	/**
	 * ============================================================
	 * 下面是内部接口的测试用例,是给开发者保证质量用的,使用者可以不往下看
	 * ============================================================
	 */
	protected function setUp()
	{
	}
	protected function tearDown()
	{
	}
}
