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
 * GoogleMapsTest
 *
 * @category Zle
 * @package  Zle_Test
 * @author   Fabio Napoleoni <f.napoleoni@gmail.com>
 * @license  http://framework.zend.com/license/new-bsd New BSD License
 * @link     http://framework.zend.com/
 */
class GoogleMapsTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Zle_View_Helper_GoogleMaps
     */
    private $_helper = null;

    protected function setUp()
    {
        $this->_helper = new Zle_View_Helper_GoogleMaps();
        $this->_helper->setView(new Zend_View());
    }

    public function testHelperHasFluentInterface()
    {
        $this->assertSame($this->_helper, $this->_helper->googleMaps());
    }

    public function testSetLocaleAcceptAZendLocaleArgument()
    {
        $l = new Zend_Locale('it_IT');
        $this->_helper->setLocale($l);
        $this->assertEquals(
            $l, $this->_helper->getLocale(),
            'Locale should be taken as is'
        );
    }

    public function testSetLocaleAcceptAString()
    {
        $this->_helper->setLocale('it_IT');
        $this->assertEquals(
            'it', $this->_helper->getLocale()->getLanguage(),
            'Locale should be taken as a string'
        );
    }

    public function testGetLocaleUseDefaultAutomaticLocale()
    {
        $this->assertEquals(new Zend_Locale(), $this->_helper->getLocale());
    }

    public function testGetLocaleUsesRegistryValueFirst()
    {
        $this->backupZendRegistryLocale();
        $locale = new Zend_Locale('it_IT');
        Zend_Registry::set('Zend_Locale', $locale);
        $this->assertEquals($locale, $this->_helper->getLocale());
        $this->restoreZendRegistryLocale();
    }

    public function testSetLocaleOverrideRegistryValue()
    {
        $this->backupZendRegistryLocale();
        $locale = new Zend_Locale('it_IT');
        $this->_helper->setLocale($locale);
        Zend_Registry::set('Zend_Locale', new Zend_Locale('en_US'));
        $this->assertEquals($locale, $this->_helper->getLocale());
        $this->restoreZendRegistryLocale();
    }

    public function testLocaleAssignmentIsEffective()
    {
        $address = 'Central Park, NY';
        $this->_helper->setLocation($address)->setLocale('fr_FR');
        $this->assertTrue(
            is_numeric(
                strpos(
                    $this->_helper->render(),
                    'hl=fr'
                )
            ),
            'Location can be set using the setLocation method'
        );
    }

    public function testSetLocationSetsTheQParameter()
    {
        $address = 'Central Park, NY';
        $this->_helper->setLocation($address);
        $this->assertTrue(
            is_numeric(
                strpos(
                    $this->_helper->render(),
                    sprintf('q=%s', urlencode($address))
                )
            ),
            'Location can be set using the setLocation method'
        );
    }

    public function testHelperIsEchoable()
    {
        $this->assertType('string', (string)$this->_helper);
    }

    public function testHeightSetter()
    {
        $value = 500;
        $this->_helper->setHeight($value);
        $this->assertEquals($value, $this->_helper->getHeight());
    }

    public function testWidthSetter()
    {
        $value = 500;
        $this->_helper->setWidth($value);
        $this->assertEquals($value, $this->_helper->getWidth());
    }

    /*
     * util methods
     */
    private $_registryLocale = null;

    protected function backupZendRegistryLocale()
    {
        if (Zend_Registry::isRegistered('Zend_Locale')) {
            $this->_registryLocale = Zend_Registry::get('Zend_Locale');
        }
    }

    protected function restoreZendRegistryLocale()
    {
        if ($this->_registryLocale) {
            Zend_Registry::set('Zend_Locale', $this->_registryLocale);
            unset($this->_registryLocale);
        }
    }
}
