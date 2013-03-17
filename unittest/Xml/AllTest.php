<?php
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . "RightWayToUse.php";
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . "WrongWayToUse.php";
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . "PerformanceTuning.php";

class UrlAllTest
{
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('PHPUnit_Framework');

        $suite->addTestSuite('RightWayToUseXml');
        $suite->addTestSuite('WrongWayToUseXml');
        $suite->addTestSuite('PerformanceTuningXml');

        return $suite;
    }
}
