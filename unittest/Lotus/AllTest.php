<?php
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . "RightWayToUse.php";
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . "WrongWayToUse.php";
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . "PerformanceTuning.php";
class LotusAllTest
{
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('PHPUnit_Framework');

        $suite->addTestSuite('RightWayToUseLotus');
        $suite->addTestSuite('WrongWayToUseLotus');
        $suite->addTestSuite('PerformanceTuningLotus');
        return $suite;
    }
}