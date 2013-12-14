<?php
/**
 * Created by PhpStorm.
 * User: qinjx
 * Date: 13-12-14
 * Time: 下午3:26
 */

class LtDomainName {
    protected $hasBeenInitialized = false;

    public function init() {
        $this->hasBeenInitialized = true;
    }

    public function getRootDomain($hostname) {
        if ($this->hasBeenInitialized) {
            if (is_string($hostname)) {
                //
            }
        } else {
            trigger_error("Please call init() first", E_USER_ERROR);
        }
        return false;
    }
}