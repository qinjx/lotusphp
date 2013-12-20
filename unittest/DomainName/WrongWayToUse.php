<?php
/**
 * 本测试文档演示了LtDomainName的错误使用方法
 * 不要按本文档描述的方式使用LtDomainName
 *
 * 没有正常初始化
 *	没调init()方法（无论是否设置过属性）@see WrongWayToUseDomainName::testNotInvokeInit()
 * 	调用了init()方法 @see RightWayToUseDomainName
 * 正常初始化了
 *  调用不成功
 *      传递给getRootDomain()的参数类型不合法 @see WrongWayToUseDomainName::testBadDomainDataType
 *      传递给getRootDomain()的参数值不合法 @see WrongWayToUseDomainName::testBadDomainValue
 *
 */
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . "common.inc.php";
class WrongWayToUseDomainName extends PHPUnit_Framework_TestCase
{
	/**
	 * 测试getRootDomain()方法不支持的参数数据类型
	 * @return array
	 */
	public function dpBadDomainDataType() {
		return array(
			array(NULL),
			array(TRUE),
			array(777),
			array(3.14),
			array(new WrongWayToUseDomainName),
		);
	}

	/**
	 * 测试getRootDomain()方法不支持的参数值
	 * @return array
	 */
	public function dpBadDomainValue() {
        return array(
            //包含ISO标准不允许的ASCII字符
            array("test-" . chr(mt_rand(1, 44)) . ".example.com"),
            array(chr(mt_rand(46, 47)) . ".example.com"),
            array("test-" . chr(mt_rand(58, 64)) . ".example.com"),
            array("test-" . chr(mt_rand(91, 96)) . ".example.com"),
            array("test-" . chr(mt_rand(123, 127)) . ".example.com"),
            //不包含ISO标准不允许的ASCII字符
            //  点号个数=0
            array("example"),
            //  点号个数>126
//            array("6.5.4.3.example.com"),
            //  点号个数介于1和126之间
            //      点号出现在字串头尾
			array("www.example."),
            array(".example.com"),
            //      点号不在头尾
            //          其中某一段长度超过63字节
            array(
                "abcdefghijklmnopqrstuvwxyz-ABCDEFGHIJKLMNOPQRSTUVWXYZ-1234567890" .//这是64个字符
                ".example.com"
            ),
            //          每一段长度都不超过63字节
            //              总长超过255字节
            array(
                "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ-1234567890." .//这是63个字符（不含点号）
                "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ-1234567890." .
                "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ-1234567890." .
                "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ-1234567890." .
                "com"
            ),
            //              总长不超过255字节
            //                  TOP Level不正确
            array("example.php"),
		);
	}

	/**
	 * @expectedException PHPUnit_Framework_Error
	 */
	public function testNotInvokeInit()
	{
		$dn = new LtDomainName;
		$dn->getRootDomain("example.com.cn");
	}

	/**
	 * @dataProvider dpBadDomainDataType
	 */
	public function testBadDomainDataType($para)
	{
		$dn = new LtDomainName;
        $dn->init();
        $this->assertNull($dn->getRootDomain($para));
	}

    /**
     * @dataProvider dpBadDomainValue
     */
    public function testBadDomainValue($para)
    {
        $dn = new LtDomainName;
        $dn->init();
        $this->assertNull($dn->getRootDomain($para));
    }

	protected function setUp()
	{
	}
	protected function tearDown()
	{
	}
}
