<?php
/**
 * 本测试文档演示了LtDomainName的正确使用方法
 * 按本文档操作一定会得到正确的结果
 *
 * 接口测试
 *      各类setXXX方法
 *      add和has方法 @see RightWayToUseDomainName::testAddAndHas()
 * 内部实现测试
 *  Hash方法
 *      相同字串多次hash得到相同结果 @see RightWayToUseDomainName::testHash1()
 *      hash结果不越界 @see RightWayToUseDomainName::testHash2()
 *      hash冲突率恰当 @todo 暂时不测试这个
 *  BitArray位操作
 *      int数组索引和bit位置计算 @see RightWayToUseDomainName::testBitArraycalcKeyAndPos()
 *      位设置和读取 @see RightWayToUseDomainName::testBitArrayOp()
 *  BitArray持久化
 *      新建文件并写入 @see RightWayToUseDomainName::testBitArraySaveAndLoad1()
 *      读取非空文件并修改和追加 @see RightWayToUseDomainName::testBitArraySaveAndLoad2()
 */
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . "common.inc.php";
class RightWayToUseDomainName extends PHPUnit_Framework_TestCase
{
	/**
	 * -------------------------------------------------------------------
	 * 本测试用例期望效果：
	 * 调用add()方法加入bit array后，调用has()方法能返回true
	 */
	public function testMostUsedWay()
	{
		/**
		 * Lotus组件初始化三步曲
		 */
		// 1. 实例化
		$dn = new LtDomainName;

		// 2. 设置属性

		// 3. 调init()方法
		$dn->init();

		//初始化完毕，测试其效果
        $this->assertEquals("example.com", $dn->getRootDomain("example.com"));
        $this->assertEquals("example.com", $dn->getRootDomain("www.example.com"));
        $this->assertEquals("google.com.hk", $dn->getRootDomain("image.google.com.hk"));
        $this->assertEquals("online.sh.cn", $dn->getRootDomain("www.online.sh.cn"));
        $this->assertEquals("sh.cn", $dn->getRootDomain("www.sh.cn"));
        $this->assertEquals("z.cn", $dn->getRootDomain("deal.z.cn"));
	}

    public function testGetRootDomainTLDRootOnly() {
        $url = new LtUrl;
        foreach ($this->TLDs as $tld) {
            $flag = mt_rand(0,1);
            if ($flag) {
                $tld = strtolower($tld);
            }
            $hostname = $this->randomAlphaString() . "." . $tld;
            $this->assertEquals($hostname, $url->getRootDomain($hostname));
        }
    }

    /**
     * @param $hostname
     * @param $exp
     *
     * @dataProvider dpGetRootDomain
     */
    public function testGetRootDomain($hostname, $exp) {
        $url = new LtUrl;
        $this->assertEquals($exp, $url->getRootDomain($hostname));
    }

    protected function randomAlphaString($min=1, $max=null) {
        if (null == $max) {
            $max = mt_rand($min,512);
        }
        $str = "";
        for ($i = 0; $i < $max; $i ++) {
            $str = chr(mt_rand(48, 90));
        }
        return $str;
    }

	protected function setUp()
	{
	}
	protected function tearDown()
	{
	}
}
