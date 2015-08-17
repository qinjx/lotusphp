<?php
$lotusHome = substr(__FILE__, 0, strpos(__FILE__, "unittest"));
require_once $lotusHome . "unittest/unittest_util.func.php";
require_once $lotusHome . "runtime/DomainParser/DomainParser.php";

/**
 * 用这个类把LtDomainParser的protected属性和方法暴露出来测试
 */
class LtDomainParserProxy extends LtDomainParser
{
    public $TLD = array(
        "aero" => 1, "asia" => 1, "biz" => 1, "cat" => 1, "com" => 1, "coop" => 1, "edu" => 1, "gov" => 1, "local" => 1,
        "info" => 1, "int" => 1, "jobs" => 1, "mil" => 1, "mobi" => 1, "name" => 1, "net" => 1, "org" => 1, "post" => 1,
        "pro" => 1, "tel" => 1, "xxx" => 1
    );

    /**
     * ccTLD
     * @var array
     * ccTLD list: http://en.wikipedia.org/wiki/ISO_3166-1_alpha-2
     * Reserved ccSLD: http://mxr.mozilla.org/mozilla-central/source/netwerk/dns/effective_tld_names.dat?raw=1
     */
    public $ccTLD = array(
        "cn" => array(
            "sh" => 1,
            "com" => 1,
        ),
        "hk" => array(
            "com" => 1,
        ),
    );

    public $allowedChars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ-1234567890";

    public function randomDomainLabel($min=1, $max=null) {
        if (null == $max) {
            $max = mt_rand($min,63);
        }
        $acLen = strlen($this->allowedChars);
        $str = "";
        for ($i = 0; $i < $max; $i ++) {
            $str .= $this->allowedChars[mt_rand(0, $acLen - 1)];
        }
        return $str;
    }
}