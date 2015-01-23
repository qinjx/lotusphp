<?php
/**
 * 本测试文档演示了LtDomainParser的正确使用方法
 * 按本文档操作一定会得到正确的结果
 *
 * 接口测试
 *      getRootDomain()方法
 *          国际域名(gTLD)
 *              二级域名 @see RightWayToUseDomainParser::testGetRootDomainTLDRootOnly()
 *              三级或者更多级的子域名 @see RightWayToUseDomainParser::testGetRootDomainTLDSubDomain()
 *          国家域名(ccTLD)
 *              二级域名 @see RightWayToUseDomainParser::testGetRootDomainCCTLDRootOnly()
 *              三级或者更多级的子域名 @see RightWayToUseDomainParser::testGetRootDomainCCTLDSubDomain()
 * 内部实现测试
 */
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . "common.inc.php";
class RightWayToUseDomainParser extends PHPUnit_Framework_TestCase
{
	/**
	 * -------------------------------------------------------------------
	 * 本测试用例期望效果：
	 * 使用getRootDomain()方法，正确解析网站域名
	 */
	public function testMostUsedWay()
	{
		/**
		 * Lotus组件初始化三步曲
		 */
		// 1. 实例化
		$dn = new LtDomainParser;

		// 2. 设置属性

		// 3. 调init()方法
		$dn->init();

		//初始化完毕，测试其效果
        $this->assertEquals("example.com", $dn->getRootDomain("example.com"));
        $this->assertEquals("example.com", $dn->getRootDomain("www.example.com"));
        $this->assertEquals("google.com.hk", $dn->getRootDomain("image.google.com.hk"));
        $this->assertEquals("online.sh.cn", $dn->getRootDomain("www.online.sh.cn"));
        $this->assertEquals("sina.com.cn", $dn->getRootDomain("www.blog.user1.sina.com.cn"));
        $this->assertEquals("www.sh.cn", $dn->getRootDomain("www.sh.cn"));
        $this->assertEquals("www.sh.cn", $dn->getRootDomain("blog.www.sh.cn"));
        $this->assertEquals("z.cn", $dn->getRootDomain("deal.z.cn"));
        $this->assertEquals("tv.ch", $dn->getRootDomain("tv.ch"));
	}

    public function testGetRootDomainTLDRootOnly() {
        $dn = new LtDomainParserProxy();
        $dn->init();
        foreach ($dn->TLD as $tld => $tmp) {
            $flag = mt_rand(0,1);
            if ($flag) {
                $tld = strtolower($tld);
            }
            $rootDomain = $dn->randomDomainLabel() . "." . $tld;
            $this->assertEquals($rootDomain, $dn->getRootDomain($rootDomain));
        }
    }

    public function testGetRootDomainTLDSubDomain() {
        $dn = new LtDomainParserProxy();
        $dn->init();
        foreach ($dn->TLD as $tld => $tmp) {
            $flag = mt_rand(0,1);
            if ($flag) {
                $tld = strtolower($tld);
            }
            $rootDomain = $dn->randomDomainLabel() . "." . $tld;
            $hostname = $rootDomain;
            for ($j = 0; $j < 3; $j ++) {
                $hostname = $dn->randomDomainLabel(1, 5) . "." . $hostname;
            }
            $this->assertEquals($rootDomain, $dn->getRootDomain($hostname));
        }
    }

    public function testGetRootDomainCCTLDRootOnly() {
        $dn = new LtDomainParserProxy();
        $dn->init();
        foreach ($dn->ccTLD as $tld => $tmp) {
            $flag = mt_rand(0,1);
            if ($flag) {
                $tld = strtolower($tld);
            }
            $rootDomain = $dn->randomDomainLabel() . "." . $tld;
            $this->assertEquals($rootDomain, $dn->getRootDomain($rootDomain));
        }
    }

    public function testGetRootDomainCCTLDSubDomain() {
        $dn = new LtDomainParserProxy();
        $dn->init();
        foreach ($dn->ccTLD as $tld => $tmp) {
            $flag = mt_rand(0,1);
            if ($flag) {
                $tld = strtolower($tld);
            }
            $rootDomain = $dn->randomDomainLabel() . "." . $tld;
            $hostname = $rootDomain;
            for ($j = 0; $j < 3; $j ++) {
                $hostname = $dn->randomDomainLabel(1, 5) . "." . $hostname;
            }
            $this->assertEquals($rootDomain, $dn->getRootDomain($hostname));
        }
    }

	protected function setUp()
	{
	}
	protected function tearDown()
	{
	}
}
