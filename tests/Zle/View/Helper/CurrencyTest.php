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
 * CurrencyTest
 *
 * @category Zle
 * @package  Zle_Test
 * @author   Fabio Napoleoni <f.napoleoni@gmail.com>
 * @license  http://framework.zend.com/license/new-bsd New BSD License
 * @link     http://framework.zend.com/
 */
class CurrencyTest extends PHPUnit_Framework_TestCase
{
    /**
     * Default locale for tests
     */
    const DEFAULT_LOCALE = 'en_US';
    
    /**
     * @var Zle_View_Helper_Currency
     */
    private $_helper = null;

    protected function setUp()
    {
        $this->_helper = new Zle_View_Helper_Currency();
        $this->_helper->setView(new Zend_View());
    }

    public function testHelperShouldThrowExceptionWithNonNumeric()
    {
        $value = array();
        try {
            $this->_helper->currency($value);
            $this->fail('Expected exception not raised');
        } catch (InvalidArgumentException $e) {
            $this->assertEquals(
                'Numeric argument expected ' . gettype($value) . ' given',
                $e->getMessage()
            );
        }
    }

    public function testHelperWithEnLocale()
    {
        $value = 5.1;
        $calculated = $this->_helper->currency(
            $value, array(), self::DEFAULT_LOCALE
        );
        $this->assertInternalType('string', $calculated, 'Helper should return a string');
        $this->assertEquals(
            '$5.10', $calculated,
            'Helper should build a localized italian string'
        );
    }

    public function testHelperAcceptOptionsForCurrency() {
        $value = 100;
        $options = array('precision' => 1);
        $this->assertEquals(
            '$100.0', $this->_helper->currency(
                $value, $options, self::DEFAULT_LOCALE
            ),
            'Helper should get the precision option'
        );
    }

    public function testOptionsDoesNotOverrideValue()
    {
        $value = 100;
        $options = array('value' => 200);
        $this->assertEquals(
             '$100.00', $this->_helper->currency(
                $value, $options, self::DEFAULT_LOCALE
             ),
             'Helper should build a localized italian string'
         );
     }
}
