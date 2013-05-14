<?php
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . "RightWayToUse.php";
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . "WrongWayToUse.php";
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . "PerformanceTuning.php";

/*
 * @todo 测试方法改进：
 * 1. protected方法测试不使用class_exists()，因容易受上次加载成功的test case影响，出现躺着通过测试的情况
 * 2. 测试用到的class文件、目录、根目录，使用随机生成的路径名和class名，不要使用事先手工建好的
 * 3. 性能测试，构造1000个文件，100个目录进行测试
 * 4. parse token的性能测试，使用autoloader类为样本，执行1000次
 * 5. 异常测试：参数设置错误、缓存、等等
 */
class AutoloaderAllTest
{
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('PHPUnit_Framework');

        $suite->addTestSuite('RightWayToUseAutoloader');
        $suite->addTestSuite('WrongWayToUseAutoloader');
        $suite->addTestSuite('PerformanceTuningAutoloader');

        return $suite;
    }
}