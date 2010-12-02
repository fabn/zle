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
 * RunnerTest
 *
 * @category Zle
 * @package  Zle_Test
 * @author   Fabio Napoleoni <f.napoleoni@gmail.com>
 * @license  http://framework.zend.com/license/new-bsd New BSD License
 * @link     http://framework.zend.com/
 */
class RunnerTest extends PHPUnit_Framework_TestCase
{
    public function testRunnerThrowsWithNonexistentClass()
    {
        try {
            Zle_Application_Runner::run('NonexistentClass');
        } catch (Zend_Application_Exception $e) {
            $this->assertContains('does not exist', $e->getMessage());
        }
    }

    public function testRunnerThrowsWithClassesWithWrongConstructor()
    {
        try {
            Zle_Application_Runner::run('PHPUnit_Framework_TestCase');
        } catch (Zend_Application_Exception $e) {
            $this->assertContains('an empty constructor', $e->getMessage());
        }
    }
}
