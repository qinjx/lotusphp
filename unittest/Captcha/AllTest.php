<?php
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . "RightWayToUse.php";
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . "WrongWayToUse.php";

class CaptchaAllTest
{
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('PHPUnit_Framework');

        $suite->addTestSuite('RightWayToUseCaptcha');
        $suite->addTestSuite('WrongWayToUseCaptcha');

        return $suite;
    }
}