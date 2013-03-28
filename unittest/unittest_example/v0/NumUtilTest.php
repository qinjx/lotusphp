<?php
/**
 * 兴高采烈地随意写了两个测试用例，不就是排除最小值嘛
 */
class TestCaseNumUtilV0 extends PHPUnit_Framework_TestCase
{
    private $numUtil;
    public function setUp()
    {
        include "NumUtil.php";
        $this->numUtil = new NumUtilV0;
    }

	public function test1()
	{
		$this->assertEquals(120, $this->numUtil->findMaxProd(array(2, 3, 4, 5, 1)));
	}

	public function test2()
	{
		$this->assertEquals(720, $this->numUtil->findMaxProd(array(6, 5, 4, 3, 2, 1,0)));
	}
}