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
 * FiscalCodeTest
 *
 * @category Zle
 * @package  Zle_Test
 * @author   Fabio Napoleoni <f.napoleoni@gmail.com>
 * @license  http://framework.zend.com/license/new-bsd New BSD License
 * @link     http://framework.zend.com/
 */
class FiscalCodeTest extends PHPUnit_Framework_TestCase
{
    public function dataProvider()
    {
        return array(
            array('RSSMRA89S11H501P', true),
            array('BNCGPP67E63F839E', true),
            array('RSSMRA89S11H501Q', false, Zle_Validate_FiscalCode::INVALID_FISCAL_CODE),
            array('RSSMRA89S11H501', false, Zle_Validate_FiscalCode::WRONG_FORMAT),
            array('', false, Zle_Validate_FiscalCode::WRONG_FORMAT),
        );
    }

    /**
     * Test fiscal code validator with multiple values
     *
     * @dataProvider dataProvider
     * @param string  $value value to test
     * @param boolean $expected expected result
     *
     * @return void
     */
    public function testFiscalCode($value, $expected, $error = null)
    {
        $validator = new Zle_Validate_FiscalCode();
        $this->assertSame($expected, $validator->isValid($value));
        if (!$expected) {
            $templates = $validator->getMessageTemplates();
            $this->assertSame($templates[$error], current($validator->getMessages()));
        }
    }
}
