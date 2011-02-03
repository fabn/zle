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
 * ExceptionTest
 *
 * @category Zle
 * @package  Zle_Test
 * @author   Fabio Napoleoni <f.napoleoni@gmail.com>
 * @license  http://framework.zend.com/license/new-bsd New BSD License
 * @link     http://framework.zend.com/
 */
class ExceptionTest extends PHPUnit_Framework_TestCase
{

    public function testExceptionIsInstantiable()
    {
        $this->assertType('Zle_Mail_Exception', new Zle_Mail_Exception());
    }

    public function testMailExceptionIsAnException()
    {
        $this->assertType('Exception', new Zle_Mail_Exception());
    }
}
