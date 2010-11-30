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
 * TableTest
 *
 * @category Zle
 * @package  Zle_Test
 * @author   Fabio Napoleoni <f.napoleoni@gmail.com>
 * @license  http://framework.zend.com/license/new-bsd New BSD License
 * @link     http://framework.zend.com/
 */
class TableTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var array simple event
     */
    private $_fields = array('timestamp' => 0,
                             'message' => 'foo',
                             'priority' => 42,
                             'priorityName' => 'bar');

    public function testFormatWithoutException()
    {
        $formatter = new Zle_Log_Formatter_Table();
        $line = $formatter->format($this->_fields);
        $this->assertContains((string)$this->_fields['timestamp'], $line);
        $this->assertContains($this->_fields['message'], $line);
        $this->assertContains($this->_fields['priorityName'], $line);
        $this->assertContains((string)$this->_fields['priority'], $line);
    }

    public function testFormatWithException()
    {
        $formatter = new Zle_Log_Formatter_Table();
        $options = array_merge(
            $this->_fields,
            array('info' => new Exception('foobar', 1234))
        );
        $line = $formatter->format($options);
        $this->assertContains('foobar', $line);
        $this->assertContains('1234', $line);
        $this->assertContains(__FILE__, $line);
    }
}
