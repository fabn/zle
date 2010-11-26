<?php

/**
 * Zend Library Extension
 *
 * PHP version 5
 *
 * @category Zle
 * @package  Zle_Validate
 * @author   Fabio Napoleoni <f.napoleoni@gmail.com>
 * @license  http://framework.zend.com/license/new-bsd New BSD License
 * @link     http://framework.zend.com/
 */

/**
 * UriTest
 *
 * @category Zle
 * @package  Zle_Validate
 * @author   Fabio Napoleoni <f.napoleoni@gmail.com>
 * @license  http://framework.zend.com/license/new-bsd New BSD License
 * @link     http://framework.zend.com/
 */
class UriTest extends PHPUnit_Framework_TestCase
{
    public function dataProvider() {
        $data = array();
        $data[] = array('http://www.example.com', true);
        $data[] = array('http://www.example.com/path', true);
        $data[] = array('http://www.example.com/path.html', true);
        $data[] = array('http://www.example.', false);
        $data[] = array('http://www..com', false);
        $data[] = array('', false);
        $data[] = array(324, false);
        return $data;
    }

    /**
     * Test uri validator with multiple values
     *
     * @dataProvider dataProvider
     * @param string  $value value to test
     * @param boolean $expected expected result
     * 
     * @return void
     */
    public function testUriValues($value, $expected)
    {
        $validator = new Zle_Validate_Uri();
        $this->assertEquals($expected, $validator->isValid($value));
    }
}
