<?php
/**
 * Created by PhpStorm.
 * User: qinjx
 * Date: 13-12-14
 * Time: 下午3:26
 */

class LtDomainName {
    public $TLD = array(
        "aero" => 1, "asia" => 1, "biz" => 1, "cat" => 1, "com" => 1, "coop" => 1, "edu" => 1, "gov" => 1, "local" => 1,
        "info" => 1, "int" => 1, "jobs" => 1, "mil" => 1, "mobi" => 1, "name" => 1, "net" => 1, "org" => 1, "post" => 1,
        "pro" => 1, "tel" => 1, "xxx" => 1
    );

    protected $ccTLD = array(
        "cn" => array(
            "sh" => 1,
        ),
        "hk" => array(
            "com" => 1,
        ),
    );
    protected $hasBeenInitialized = false;
    protected $domainLabels = array();

    public function init() {
        $this->hasBeenInitialized = true;
    }

    public function getRootDomain($hostname) {
        if ($this->hasBeenInitialized) {
            if (is_string($hostname) && true === $this->isValidSubDomain($hostname)) {
                $sld = $this->domainLabels[1] . "." . $this->domainLabels[0];
                if (2 === count($this->domainLabels)) {
                    return $sld;
                } else {
                    if (3 <= strlen($this->domainLabels[0])) {//gTLD
                        return $sld;
                    } else {//ccTLD
                        if ("www" === $this->domainLabels[2]) {
                            return $sld;
                        } else if (isset($this->ccTLD[$this->domainLabels[0]][$this->domainLabels[1]])) {//Reserved ccSLD
                            return $this->domainLabels[2] . "." . $sld;
                        } else {
                            return $sld;
                        }
                    }
                }
            }
        } else {
            trigger_error("Please call init() first", E_USER_ERROR);
        }
        return false;
    }

    protected function isValidSubDomain($hostname) {
        if ("." !== substr($hostname, 0, 1) && "." !== substr($hostname, -1) && 255 > strlen($hostname)) {
            $labels = explode(".", $hostname);
            $labelsNum = count($labels);
            if (1 < $labelsNum && 6 > $labelsNum) {
                if (
                    3 <= strlen($labels[$labelsNum-1]) && !isset($this->TLD[$labels[$labelsNum-1]])
                    or
                    2 === strlen($labels[$labelsNum-1]) && !isset($this->ccTLD[$labels[$labelsNum-1]])
                ) {
                    return false;
                }
                for ($i = $labelsNum-1; $i >= 0; $i --) {
                    if (true === $this->isValidDomainLabel($labels[$i])) {
                        if ($i >= $labelsNum - 3) {
                            $this->domainLabels[$labelsNum - $i - 1] = $labels[$i];
                        }
                    } else {
                        return false;
                    }
                }
                return true;
            }
        }
        return false;
    }

    protected function isValidDomainLabel($label) {
        if (63 >= strlen($label)) {
            $labelLen = strlen($label);
            for($i = 0; $i < $labelLen; $i ++) {
                $ascii = ord($label[$i]);
                if (
                    $ascii >= 65 && $ascii <= 90 //A-Z
                    or
                    $ascii >= 97 && $ascii <= 122//a-z
                    or
                    $ascii >= 48 && $ascii <= 57 //0-9
                    or
                    $ascii == 45
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