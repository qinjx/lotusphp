<?php
include "NumUtil.php";
include "../v0/NumUtil.php";

/**
 * 哎呀，上一版没考虑0和负数，补点测试用例
 */
class TestCaseNumUtilV1 extends PHPUnit_Framework_TestCase
{
    private $numUtil;
    public function setUp()
    {
        $this->numUtil = new NumUtilV0;
//        $this->numUtil = new NumUtilV1;
    }

    /**
     * 零的个数大于1
     * 本来根据根据负数个数奇偶性、正数有无可以分成四种情况
     * 但这四种情况明显可以归并到这一种，因此不再分成四个条件来写
     */
    public function test_amountOfZeroGreaterThanOne()
    {
        $this->assertEquals(0, $this->numUtil->findMaxProd(array(0, 0, 1, 2, 3, 4)));
    }

    /**
     * 零的个数等于1 偶数个负数 有正数
     */
    public function test_amountOfZeroEqualsOne_amountOfNegativeIsEven_existsPositive()
    {
        $this->assertEquals(200, $this->numUtil->findMaxProd(array(0, -1, -2, 10, 5, 2)));
    }

    /**
     * 零的个数等于1 偶数个负数 无正数
     */
    public function test_amountOfZeroEqualsOne_amountOfNegativeIsEven_notExistsPositive()
    {
        $this->assertEquals(100, $this->numUtil->findMaxProd(array(0, -1, -2, -10, -5)));
    }

    /**
     * 零的个数等于1 奇数个负数 有正数
     */
    public function test_amountOfZeroEqualsOne_amountOfNegativeIsOdd_existsPositive()
    {
        $this->assertEquals(0, $this->numUtil->findMaxProd(array(0, -1, 2, 3)));
    }

    /**
     * 零的个数等于1 奇数个负数 无正数
     */
    public function test_amountOfZeroEqualsOne_amountOfNegativeIsOdd_notExistsPositive()
    {
        $this->assertEquals(0, $this->numUtil->findMaxProd(array(0, -1, -2, -3)));
    }

    /**
     * 零的个数小于1 偶数个负数 有正数
     */
    public function test_amountOfZeroLessThanOne_amountOfNegativeIsEven_existsPositive()
    {
        $this->assertEquals(100, $this->numUtil->findMaxProd(array( -1, -2, -10, -5, 10)));
    }

    /**
     * 零的个数小于1 偶数个负数 无正数
     */
    public function test_amountOfZeroLessThanOne_amountOfNegativeIsEven_notExistsPositive()
    {
        $this->assertEquals(-10, $this->numUtil->findMaxProd(array( -1, -2, -1024, -5)));
    }

    /**
     * 零的个数小于1 奇数个负数 有正数
     */
    public function test_amountOfZeroLessThanOne_amountOfNegativeIsOdd_existsPositive()
    {
        $this->assertEquals(200, $this->numUtil->findMaxProd(array(-5, -10, -2, 4)));
    }

    /**
     * 零的个数小于1 奇数个负数 无正数
     */
    public function test_amountOfZeroLessThanOne_amountOfNegativeIsOdd_notExistsPositive()
    {
        $this->assertEquals(50, $this->numUtil->findMaxProd(array(-5, -10, -2)));
    }
}