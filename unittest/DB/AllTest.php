<?php
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . "RightWayToUse.php";
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . "WrongWayToUse.php";
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . "PerformanceTuning.php";

class DbAllTest
{
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('PHPUnit_Framework');

        $suite->addTestSuite('RightWayToUseDb');
        $suite->addTestSuite('WrongWayToUseDb');
        $suite->addTestSuite('PerformanceTuningDb');

        return $suite;
    }
}