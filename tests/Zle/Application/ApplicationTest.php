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

    public function testConfigIsReloadedWhenNewValuesAreAdded()
    {
        $key = 'bar';
        $app = new Zle_Application('testing', $this->getConfigFile());
        $old = $app->getOption($key);
        $random = uniqid();
        clearstatcache();
        sleep(1);
        system("echo 'bar={$random}' >> {$this->getConfigFile()}");
        $app = new Zle_Application('testing', $this->getConfigFile());
        $this->assertEquals($random, $app->getOption($key));
        $this->assertNotEquals($old, $app->getOption($key));
    }

    public function testConfigIsCachedAcrossInstances()
    {
        $key = 'bar';
        $app = new Zle_Application('testing', $this->getConfigFile());
        $old = $app->getOption($key);
        $app = new Zle_Application('testing', $this->getConfigFile());
        $this->assertEquals($old, $app->getOption($key));
    }

    public function testConfigIsReloadedWhenCacheFails()
    {
        $key = 'bar';
        $app = new Zle_Application('testing', $this->getConfigFile());
        $old = $app->getOption($key);
        $cache = Zend_Cache::factory(
            'Core', 'Apc',
            array('automatic_serialization' => true)
        );
        $cache->remove(Zle_Application::CONFIG_ARRAY_KEY);
        $app = new Zle_Application('testing', $this->getConfigFile());
        $this->assertEquals($old, $app->getOption($key));
    }
}
