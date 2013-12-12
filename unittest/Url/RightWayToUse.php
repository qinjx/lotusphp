<?php
/**
 * 本测试文档演示了Url的正确使用方法 
 * 按本文档操作一定会得到正确的结果
 *
 * 测试URL generating功能
 *
 * 测试get root domain功能
 *  TLD（TOP Level Domain）
 *      仅root domain @see RightWayToUseUrl::testGetRootDomain2CTLDRootOnly()
 *      有二级hostname
 *  ccTLD（Country Code Top Level Domain）
 *      英文的ccTLD（只有2位的）
 *      非英文的ccTLD @todo 此种情况不支持
 */
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . "common.inc.php";
class RightWayToUseUrl extends PHPUnit_Framework_TestCase
{

	public function testMostUsedWay()
	{
		// 默认的module和action的名字
		$config['router.routing_table']['default'] = array('module' => 'default', 'action' => 'index');
		// URL中变量的分隔符号
		$config['router.routing_table']['delimiter'] = '-';
		// 后缀，常用来将URL模拟成单个文件
		$config['router.routing_table']['postfix'] = '.html';
		// REWRITE STANDARD PATH_INFO 三种模式，不分大小写
		$config['router.routing_table']['protocol'] = 'standard';
		$configHandle = new LtConfig();
		$configHandle->addConfig($config);

		// 初始化LtUrl
		$url = new LtUrl;
		$url->configHandle = $configHandle;
		$url->init(); 
		// 初始化结束
		// 测试生成超链接
		$href = $url->generate('news', 'list', array('catid' => 4, 'page' => 10));
		$this->assertEquals('/index.php?module=news&action=list&catid=4&page=10', $href);
	}
	
	public function testOther()
	{
		$url = new LtUrl;
		$url->init();
		$params = array(
				'id' => 123456,
				'page' => '12',
				//'void' => null,
				'q-/key' => '空 -/格',
				//'empty' => '',
		);
		$baseUrl = 'http://localhost';
		$link = $url->generate('goods', 'detail', $params, $baseUrl, 'rewrite');
		$this->assertEquals('http://localhost/goods-detail-id-123456-page-12-q%FF/key-%E7%A9%BA%20%FF%2F%E6%A0%BC.html', $link);
	
		$baseUrl = 'http://127.0.0.1';
		$link = $url->generate('goods', 'detail', $params, $baseUrl, 'rewrite');
		$this->assertEquals('http://127.0.0.1/goods-detail-id-123456-page-12-q%FF/key-%E7%A9%BA%20%FF%2F%E6%A0%BC.html', $link);
		
		$link = $url->generate('goods', 'detail', $params, $baseUrl, 'path_info');
		$this->assertEquals('http://127.0.0.1/index.php/goods/detail/id/123456/page/12/q-%FFkey/%E7%A9%BA%20-%FF%E6%A0%BC.html', $link);
		
		$link = $url->generate('goods', 'detail', $params, $baseUrl, 'standard');
		$this->assertEquals('http://127.0.0.1/index.php?module=goods&action=detail&id=123456&page=12&q-%2Fkey=%E7%A9%BA%20-%2F%E6%A0%BC', $link);
		
		$link2 = $url->getLink('goods', 'detail', $params, $baseUrl);
		$this->assertEquals($link, $link2);
	}

	public function testPathinfo()
	{
		$config['router.routing_table']['protocol'] = 'PATH_INFO';
		$configHandle = new LtConfig();
		$configHandle->addConfig($config);
	
		$url = new LtUrl;
		$url->configHandle = $configHandle;
		$url->init();
		
		$params = array(
				'id' => 123456,
				'page' => '12',
				//'void' => null,
				'q-/key' => '空 -/格',
				//'empty' => '',
		);
		$url->baseUrl = 'http://localhost';
		$link = $url->generate('goods', 'detail', $params);
		$this->assertEquals('http://localhost/index.php/goods/detail/id/123456/page/12/q-%FFkey/%E7%A9%BA%20-%FF%E6%A0%BC.html', $link);

		$url->baseUrl = 'http://127.0.0.1';
		$link = $url->generate('goods', 'detail', $params);
		$this->assertEquals('http://127.0.0.1/index.php/goods/detail/id/123456/page/12/q-%FFkey/%E7%A9%BA%20-%FF%E6%A0%BC.html', $link);
		
	}
	
	public function testRewrite()
	{
		$config['router.routing_table']['protocol'] = 'REWRITE';
		$configHandle = new LtConfig();
		$configHandle->addConfig($config);
	
		$url = new LtUrl;
		$url->configHandle = $configHandle;
		$url->init();
	
		$params = array(
				'id' => 123456,
				'page' => '12',
				//'void' => null,
				'q-/key' => '空 -/格',
				//'empty' => '',
		);
		$url->baseUrl = 'http://localhost';
		$link = $url->generate('goods', 'detail', $params);
		$this->assertEquals('http://localhost/goods-detail-id-123456-page-12-q%FF/key-%E7%A9%BA%20%FF%2F%E6%A0%BC.html', $link);
	
		$url->baseUrl = 'http://127.0.0.1';
		$link = $url->generate('goods', 'detail', $params);
		$this->assertEquals('http://127.0.0.1/goods-detail-id-123456-page-12-q%FF/key-%E7%A9%BA%20%FF%2F%E6%A0%BC.html', $link);
	
	}

    public $TLDs = array(
        "aero","asia","biz","cat","com","coop","edu","gov","local","info","int","jobs","mil","mobi","name","net","org",
        "post","pro","tel","xxx",
    );

    /**
     * @var array
     * Data source: http://en.wikipedia.org/wiki/ISO_3166-1_alpha-2
     */
    public $ccTLDs = array(
        "AD","AE","AF","AG","AI","AL","AM","AO","AQ","AR","AS","AT","AU","AW","AX","AZ","BA","BB","BD","BE","BF","BG",
        "BH","BI","BJ","BL","BM","BN","BO","BQ","BR","BS","BT","BV","BW","BY","BZ","CA","CC","CD","CF","CG","CH","CI",
        "CK","CL","CM","CN","CO","CR","CU","CV","CW","CX","CY","CZ","DE","DJ","DK","DM","DO","DZ","EC","EE","EG","EH",
        "ER","ES","ET","FI","FJ","FK","FM","FO","FR","GA","GB","GD","GE","GF","GG","GH","GI","GL","GM","GN","GP","GQ",
        "GR","GS","GT","GU","GW","GY","HK","HM","HN","HR","HT","HU","ID","IE","IL","IM","IN","IO","IQ","IR","IS","IT",
        "JE","JM","JO","JP","KE","KG","KH","KI","KM","KN","KP","KR","KW","KY","KZ","LA","LB","LC","LI","LK","LR","LS",
        "LT","LU","LV","LY","MA","MC","MD","ME","MF","MG","MH","MK","ML","MM","MN","MO","MP","MQ","MR","MS","MT","MU",
        "MV","MW","MX","MY","MZ","NA","NC","NE","NF","NG","NI","NL","NO","NP","NR","NU","NZ","OM","PA","PE","PF","PG",
        "PH","PK","PL","PM","PN","PR","PS","PT","PW","PY","QA","RE","RO","RS","RU","RW","SA","SB","SC","SD","SE","SG",
        "SH","SI","SJ","SK","SL","SM","SN","SO","SR","SS","ST","SV","SX","SY","SZ","TC","TD","TF","TG","TH","TJ","TK",
        "TL","TM","TN","TO","TR","TT","TV","TW","TZ","UA","UG","UM","US","UY","UZ","VA","VC","VE","VG","VI","VN","VU",
        "WF","WS","YE","YT","ZA","ZM","ZW"
    );

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
		$_SERVER['SCRIPT_NAME'] = '/index.php';
		$_SERVER['PHP_SELF'] = '/index.php';
	}
	protected function tearDown()
	{
	}
}
