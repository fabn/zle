<?php

/**
 * Zend Library Extension
 *
 * PHP version 5
 *
 * @category Zle
 * @package  Zle_Test
 * @author   Fabio Napoleoni <f.napoleoni@gmail.com>
 * @license  http://framework.zend.com/license/new-bsd New BSD License
 * @link     http://framework.zend.com/
 */

/**
 * DateCompareTest
 *
 * @category Zle
 * @package  Zle_Test
 * @author   Andrea Giannantonio <a.giannantonio@gmail.com>
 * @license  http://framework.zend.com/license/new-bsd New BSD License
 * @link     http://framework.zend.com/
 */
class DateCompareTest extends PHPUnit_Framework_TestCase
{
    public function dataForTest()
    {
        return array(
            array('2011-06-05', '2011-06-01', '2011-06-10', true), //between dates
            array('2011-06-10', '2011-06-01', '2011-06-10', true), //between dates
            array('2011-06-01', '2011-06-01', '2011-06-10', true), //between dates
            array('2011-06-15', '2011-06-01', '2011-06-10', false), //between dates
            array('2011-05-30', '2011-06-01', '2011-06-10', false), //between dates
            array('2011-06-01', '2011-06-01', null, true), //exact match
            array('2011-06-15', '2011-06-01', null, false), //exact match
            array('2011-05-30', '2011-06-01', null, false), //exact match
            array('2011-06-02', '2011-06-01', true, true), //not later
            array('2011-06-01', '2011-06-01', true, false), //not later
            array('2011-05-30', '2011-06-01', true, false), //not later
            array('2011-05-30', '2011-06-01', false, true), //not earlier
            array('2011-06-01', '2011-06-01', false, false), //not earlier
            array('2011-06-10', '2011-06-01', false, false), //not earlier
        );
    }

    /**
     * @dataProvider dataForTest
     * @param $value
     * @param $token
     * @param $compare
     * @param $expected
     * @return void
     */
    public function testDateCompare($value, $token, $compare, $expected)
    {
        /** @var $validate Zle_Validate_DateCompare */
        $validate = new Zle_Validate_DateCompare($token, $compare);
        $this->assertEquals(
            $expected,
            $validate->isValid($value),
            "value: $value -- token: $token -- compare: $compare"
        );
    }

}
