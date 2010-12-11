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
 * NotifierTest
 *
 * @category Zle
 * @package  Zle_Test
 * @author   Fabio Napoleoni <f.napoleoni@gmail.com>
 * @license  http://framework.zend.com/license/new-bsd New BSD License
 * @link     http://framework.zend.com/
 */
class NotifierTest extends PHPUnit_Framework_TestCase
{

    protected $logOptions = array(
        'resources' => array(
            'log' => array(
                'stream' => array(
                    'writerName' => 'Stream',
                    'writerParams' => array(
                        'stream' => "php://memory",
                        'mode' => 'a'
                    ),
                    'filterName' => 'Priority',
                    'filterParams' => array(
                        'priority' => '4'
                    ),
                ),
            ),
        ),
    );

    protected $resourceOptions = array(
        'addresses' => 'foo@example.org',
    );

    protected function setUp()
    {
        // initialize an app and a bootstrap
        $this->application = new Zend_Application('testing');
        $this->bootstrap = new Zend_Application_Bootstrap_Bootstrap($this->application);
    }

    public function testResourceDependsOnLogResource()
    {
        try {
            $resource = new Zle_Application_Resource_Notifier();
            $resource->setBootstrap($this->bootstrap);
            $resource->setOptions($this->resourceOptions);
            $resource->init();
            $this->fail();
        } catch (Exception $e) {
            $this->assertContains(
                'depends on Zend_Application_Resource_Log',
                $e->getMessage()
            );
        }
    }

    public function testResourceIsInitializedWhenParametersAreSet()
    {
        $this->bootstrap->setOptions($this->logOptions);
        $resource = new Zle_Application_Resource_Notifier();
        $resource->setBootstrap($this->bootstrap);
        $resource->setOptions($this->resourceOptions);
        $this->assertTrue($resource->init() instanceof Zle_Log_Writer_Mail);
    }

    public function testLogHasOneMoreWriterAfterResourceInit()
    {
        $this->bootstrap->setOptions($this->logOptions);
        $resource = new Zle_Application_Resource_Notifier();
        $resource->setBootstrap($this->bootstrap);
        $resource->setOptions($this->resourceOptions);
        $resource->init();
        $log = $resource->getBootstrap()->getResource('log');
        $this->assertContains(
            'Zle_Log_Writer_Mail',
            var_export($log, true),
            'Log object should contain Zle_Log_Writer_Mail as Writer'
        );
    }

    public function testFlagDisableNotifier()
    {
        $this->bootstrap->setOptions($this->logOptions);
        $resource = new Zle_Application_Resource_Notifier();
        $resource->setBootstrap($this->bootstrap);
        $resource->setOptions(
            array_merge($this->resourceOptions, array('disabled' => true))
        );
        $resource->init();
        $log = $resource->getBootstrap()->getResource('log');
        $this->assertNotContains(
            'Zle_Log_Writer_Mail',
            var_export($log, true),
            'Log object should contain Zle_Log_Writer_Mail as Writer'
        );
    }
}
