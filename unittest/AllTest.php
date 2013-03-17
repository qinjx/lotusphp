<?php
/**
 * @todo 所有的WrongWayToUse都加上不按流程调用Lotus组件
 *       例如没有set prop或者init()就开始使用组件的功能了
 */
//测试Lotus框架
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . "Lotus/AllTest.php";
//测试各组件
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . "Autoloader/AllTest.php";
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . "Cache/AllTest.php";
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . "Captcha/AllTest.php";
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . "Config/AllTest.php";
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . "Cookie/AllTest.php";
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . "DB/AllTest.php";
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . "MVC/AllTest.php";
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . "ObjectUtil/AllTest.php";
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . "Pagination/AllTest.php";
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . "RBAC/AllTest.php";
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . "Router/AllTest.php";
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . "Store/AllTest.php";
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . "Url/AllTest.php";
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . "Validator/AllTest.php";

class AllTest
{
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('PHPUnit_Framework');
        $suite->addTestSuite('AutoloaderAllTest');
        $suite->addTestSuite('CacheAllTest');
        $suite->addTestSuite('CaptchaAllTest');
        $suite->addTestSuite('ConfigAllTest');
        $suite->addTestSuite('CookieAllTest');
        $suite->addTestSuite('DbAllTest');
        $suite->addTestSuite('MVCAllTest');
        $suite->addTestSuite('ObjectUtilAllTest');
        $suite->addTestSuite('PaginationAllTest');
        $suite->addTestSuite('RBACAllTest');
        $suite->addTestSuite('RouterAllTest');
        $suite->addTestSuite('StoreAllTest');
        $suite->addTestSuite('UrlAllTest');
        $suite->addTestSuite('ValidatorAllTest');
        $suite->addTestSuite('LotusAllTest');

        return $suite;
    }
}