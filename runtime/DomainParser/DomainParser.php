<?php
/**
 * Created by PhpStorm.
 * User: qinjx
 * Date: 13-12-14
 * Time: 下午3:26
 */

class LtDomainParser {
    protected $TLD = array(
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
    protected $ccTLD = array(
        "cn" => array(
            "sh" => 1,
            "com" => 1,
        ),
        "hk" => array(
            "com" => 1,
        ),
        "ch" => 1,
    );

    protected $hasBeenInitialized = false;

    public function init() {
        $this->hasBeenInitialized = true;
    }

    /**
     * 从URL中的主机名解析出网站根域名
     * @param string $hostname URL中的主机名
     * @return bool|string 网站根域名
     */
    public function getRootDomain($hostname) {
        if ($this->hasBeenInitialized) {
            if (is_string($hostname) && $Last3Tokens = $this->getValidLast3DomainLabels($hostname)) {
                $sld = $Last3Tokens[1] . "." . $Last3Tokens[0];
                if (3 <= strlen($Last3Tokens[0])) {//gTLD
                    if (isset($this->TLD[$Last3Tokens[0]])) {
                        return $sld;
                    }
                } else {//ccTLD
                    if (isset($this->ccTLD[$Last3Tokens[0]])) {//ccTLD
                        if (isset($this->ccTLD[$Last3Tokens[0]][$Last3Tokens[1]])) {//Reserved ccSLD
                            if (isset($Last3Tokens[2])) {
                                return $Last3Tokens[2] . "." . $sld;
                            }
                        } else {
                            return $sld;
                        }
                    }
                }
            }
        } else {
            trigger_error("Please call init() first", E_USER_ERROR);
        }
        return null;
    }

    /**
     * 获取主机名最后三段，如果只有两段，返回两段
     * @param $hostname
     * @return array|null
     */
    protected function getValidLast3DomainLabels($hostname) {
        if ("." !== substr($hostname, 0, 1) && "." !== substr($hostname, -1) && 253 > strlen($hostname)) {
            $labels = explode(".", $hostname);
            $labelsNum = count($labels);
            if (2 <= $labelsNum && 127 >= $labelsNum) {
                $Last3Tokens = array();
                for ($i = $labelsNum-1; $i >= 0; $i --) {
                    if (true === $this->isValidDomainLabel($labels[$i])) {
                        if ($i >= $labelsNum - 3) {
                            $Last3Tokens[$labelsNum - $i - 1] = $labels[$i];
                        }
                    } else {
                        return null;
                    }
                }
                return $Last3Tokens;
            }
        }
        return null;
    }

    /**
     * 判断是否合法的域名，主要条件：最长63字节，不可包含非法字符
     * @param $label
     * @return bool
     */
    protected function isValidDomainLabel($label) {
        $labelLen = strlen($label);
        if (63 >= $labelLen && 1 <= $labelLen) {
            for($i = 0; $i < $labelLen; $i ++) {
                $ascii = ord($label[$i]);
                if (
                    $ascii >= 65 && $ascii <= 90 //A-Z
                    or
                    $ascii >= 97 && $ascii <= 122//a-z
                    or
                    $ascii >= 48 && $ascii <= 57 //0-9
                    or
                    $ascii == 45//-
                ) {
                    // it is valid
                } else {
                    return false;
                }
            }
            return true;
        } else {
            return false;
        }
    }
}