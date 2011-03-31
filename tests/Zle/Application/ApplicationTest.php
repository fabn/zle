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
 * ApplicationTest.php
 *
 * @category Zle
 * @package  Zle_Test
 * @author   Fabio Napoleoni <f.napoleoni@gmail.com>
 * @license  http://framework.zend.com/license/new-bsd New BSD License
 * @link     http://framework.zend.com/
 */
class ApplicationTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Zle_Application
     */
    protected $application;

    const CONFIG = '_files/config.ini';

    protected function setUp()
    {
        copy($this->getConfigFile() . '.dist', $this->getConfigFile());
    }

    protected function tearDown()
    {
        // remove test file
        unlink($this->getConfigFile());
        // clear the cache
        $this->assertTrue(apc_clear_cache("user"));
    }

    protected function getConfigFile()
    {
        return __DIR__ . DIRECTORY_SEPARATOR . self::CONFIG;
    }

    /**
     * @return Zle_Application
     */
    protected function getMockApplication()
    {
        return $this->getMock(
            'Zle_Application', array('getConfigFromCache'),
            array(), '', false
        );
    }

    public function testLoadConfigIsLoadedTheFirstTime()
    {
        /** @var $application Zle_Application */
        $application = $this->getMockApplication();
        $application->expects($this->never())->method('getConfigFromCache');
        $application->__construct('testing', $this->getConfigFile());
    }

    public function testLoadConfigIsNotLoadedWhenCalledForAnotherInstance()
    {
        new Zle_Application('testing', $this->getConfigFile());
        /** @var $application Zle_Application */
        $application = $this->getMockApplication();
        $application->expects($this->once())->method('getConfigFromCache');
        $application->__construct('testing', $this->getConfigFile());
    }


    public function testConfigIsReloadedWhenFileIsTouched()
    {
        /** @var $application Zle_Application */
        $application = $this->getMockApplication();
        sleep(1);
        touch($this->getConfigFile());
        $application->expects($this->never())->method('getConfigFromCache');
        $application->__construct('testing', $this->getConfigFile());
    }

    public function testConfigIsReloadedWhenFileIsUpdated()
    {
        /** @var $application Zle_Application */
        $application = $this->getMockApplication();
        system("echo ';comment' >> {$this->getConfigFile()}");
        $application->expects($this->never())->method('getConfigFromCache');
        $application->__construct('testing', $this->getConfigFile());
    }

    /**
     * @expectedException Zend_Application_Exception
     * @return void
     */
    public function testApplicationThrowsWhenInvalidFileIsGiven()
    {
        new Zle_Application('testing', 'foo');
    }
}
