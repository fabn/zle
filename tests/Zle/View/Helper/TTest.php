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
 * TTest
 *
 * @category Zle
 * @package  Zle_Test
 * @author   Fabio Napoleoni <f.napoleoni@gmail.com>
 * @license  http://framework.zend.com/license/new-bsd New BSD License
 * @link     http://framework.zend.com/
 */
class TTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Zle_View_Helper_T
     */
    private $_helper = null;

    protected function setUp()
    {
        $this->_helper = new Zle_View_Helper_T();
        $this->_helper->setView(new Zend_View());
    }

    public function testTIsTheSameAsTranslate()
    {
        $translate = new Zend_View_Helper_Translate();
        $this->assertSame($translate->translate('foo'), $this->_helper->t('foo'));
    }

    public function testUntranslatedStrings()
    {
        $text = 'foobar';
        $this->assertEquals($text, $this->_helper->t($text));
    }

    public function testTAcceptVarArgsAsArray()
    {
        $msg = '%1$s %2$s';
        $args = array('foo', 'bar');
        $this->assertEquals('foo bar', $this->_helper->t($msg, $args));
    }

    public function testTAcceptVarArgsAsList()
    {
        $msg = '%1$s %2$s';
        $this->assertEquals('foo bar', $this->_helper->t($msg, 'foo', 'bar'));
    }

    public function testTAcceptVarArgsAsListWithInvertedArgs()
    {
        $msg = '%2$s %1$s';
        $this->assertEquals('bar foo', $this->_helper->t($msg, 'foo', 'bar'));
    }
}
