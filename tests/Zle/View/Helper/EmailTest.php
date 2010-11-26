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
 * EmailTest
 *
 * @category Zle
 * @package  Zle_Test
 * @author   Fabio Napoleoni <f.napoleoni@gmail.com>
 * @license  http://framework.zend.com/license/new-bsd New BSD License
 * @link     http://framework.zend.com/
 */
class EmailTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var string test email address
     */
    private $_emailAddress = 'foo@example.com';

    /**
     * @var Zle_View_Helper_Email
     */
    private $_helper = null;

    protected function setUp()
    {
        $this->_helper = new Zle_View_Helper_Email();
        $this->_helper->setView(new Zend_View());
    }

    public function testLinkIsFormattedCorrectly()
    {
        $expectedValue = '<a href="mailto:' . $this->_emailAddress . '">'
                         . $this->_emailAddress . '</a>';
        $this->assertEquals(
            $expectedValue,
            $this->_helper->email($this->_emailAddress),
            'Link should be formatted correctly'
        );
    }

    public function testTextIsUsedForLinkWhenGiven()
    {
        $text = 'Link Text';
        $expectedValue = '<a href="mailto:' . $this->_emailAddress . '">'
                         . $text . '</a>';
        $this->assertEquals(
            $expectedValue,
            $this->_helper->email($this->_emailAddress, $text),
            'Text should be used in link'
        );
    }

    public function testTextIsEscapedByDefault()
    {
        $text = '<b>Link Text</b>';
        $expectedValue = '<a href="mailto:' . $this->_emailAddress . '">'
                         . '&lt;b&gt;Link Text&lt;/b&gt;</a>';
        $this->assertEquals(
            $expectedValue,
            $this->_helper->email($this->_emailAddress, $text),
            'Text should be used in link'
        );
    }

    public function testTextIsNotEscapedWhenRequested()
    {
        $text = '<b>Link Text</b>';
        $expectedValue = '<a href="mailto:' . $this->_emailAddress . '">'
                         . $text . '</a>';
        $this->assertEquals(
            $expectedValue,
            $this->_helper->email($this->_emailAddress, $text, false),
            'Text should be used in link'
        );
    }
}
