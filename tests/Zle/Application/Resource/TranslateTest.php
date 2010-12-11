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
 * TranslateTest
 *
 * @category Zle
 * @package  Zle_Test
 * @author   Fabio Napoleoni <f.napoleoni@gmail.com>
 * @license  http://framework.zend.com/license/new-bsd New BSD License
 * @link     http://framework.zend.com/
 */
class TranslateTest extends PHPUnit_Framework_TestCase
{

    protected $cacheOptions = array(
        'resources' => array(
            'cachemanager' => array(
                Zle_Application_Resource_Translate::DEFAULT_CACHE_KEY => array(
                    'frontend' => array(
                        'name' => 'Core',
                        'options' => array(
                            'lifetime' => 7200,
                            'automatic_serialization' => true
                        )
                    ),
                    'backend' => array(
                        'name' => 'File',
                        'options' => array(
                            'cache_dir' => '/tmp/'
                        )
                    )
                )
            ),
        ),
    );

    protected $resourceOptions = array(
        'data' => array(
            'message1' => 'message1',
            'message2' => 'message2',
            'message3' => 'message3'
        ),
        'cacheKey' => Zle_Application_Resource_Translate::DEFAULT_CACHE_KEY,
        'cacheEnabled' => false,
    );

    /**
     * @var Zend_Application
     */
    protected $application;

    /**
     * @var Zend_Application_Bootstrap_Bootstrapper
     */
    protected $bootstrap;

    /**
     * @var Zend_Cache_Core
     */
    protected $cache;

    protected function setUp()
    {
        // initialize an app and a bootstrap
        $this->application = new Zend_Application('testing');
        $this->bootstrap = new Zend_Application_Bootstrap_Bootstrap($this->application);
        // save static variables
        if (Zend_Translate_Adapter::hasCache()) {
            $this->cache = Zend_Translate_Adapter::getCache();
        }
    }

    protected function tearDown()
    {
        // restore static variables
        if (isset($this->cache)) {
            Zend_Translate_Adapter::setCache($this->cache);
            unset($this->cache);
        } else {
            Zend_Translate_Adapter::removeCache();
        }
        // unset the registry
        Zend_Registry::_unsetInstance();
    }

    public function testTranslatorHasALoggerWhenLogIsSet()
    {
        $options = array('log' => array(
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
        ));
        $resource = new Zle_Application_Resource_Translate();
        $resource->setBootstrap($this->bootstrap);
        $resource->setOptions(array_merge($this->resourceOptions, $options));
        $translate = $resource->init();
        $this->assertTrue(
            ($translate instanceof Zend_Translate),
            "Translator should be of type Zend_Translate, instead was "
            . get_class($translate)
        );
        $adapterOptions = $translate->getAdapter()->getOptions();
        $this->assertTrue(
            $adapterOptions['log'] instanceof Zend_Log,
            'Log options shoud be of type Zend_Log, instead was '
            . get_class($adapterOptions['log'])
        );
    }

    public function testTranslatorDoesNotHaveLoggerWhenKeyIsFalse()
    {
        $options = array('log' => false);
        $resource = new Zle_Application_Resource_Translate();
        $resource->setBootstrap($this->bootstrap);
        $resource->setOptions(array_merge($this->resourceOptions, $options));
        $resource->setOptions($this->resourceOptions);
        $translate = $resource->init();
        $this->assertTrue(
            ($translate instanceof Zend_Translate),
            "Translator should be of type Zend_Translate, instead was "
            . get_class($translate)
        );
        $adapterOptions = $translate->getAdapter()->getOptions();
        $this->assertNull($adapterOptions['log']);
    }

    public function testResourceThrowsWithNoCacheManager()
    {
        try {
            $options = array('cacheEnabled' => true);
            $resource = new Zle_Application_Resource_Translate();
            $resource->setBootstrap($this->bootstrap);
            $resource->setOptions(array_merge($this->resourceOptions, $options));
            $resource->init();
            $this->fail('Exception not raised');
        } catch (Zend_Application_Resource_Exception $e) {
            $this->assertContains('configure the cachemanager', $e->getMessage());
        }
    }

    public function testResourceThrowsWithWrongKeyForCacheManager()
    {
        try {
            $options = array('cacheEnabled' => true, 'cacheKey' => 'wrongKey');
            $this->bootstrap->setOptions($this->cacheOptions);
            $resource = new Zle_Application_Resource_Translate();
            $resource->setBootstrap($this->bootstrap);
            $resource->setOptions(array_merge($this->resourceOptions, $options));
            $resource->init();
            $this->fail('Exception not raised');
        } catch (Zend_Application_Resource_Exception $e) {
            $this->assertContains('configure the cachemanager', $e->getMessage());
        }
    }

    public function testTranslatorHasCacheWithDefaultKeyWithEnabledToTrue()
    {
        $options = array('cacheEnabled' => true);
        $this->bootstrap->setOptions($this->cacheOptions);
        $resource = new Zle_Application_Resource_Translate();
        $resource->setBootstrap($this->bootstrap);
        $resource->setOptions(array_merge($this->resourceOptions, $options));
        $translate = $resource->init();
        $cache = $this->bootstrap->getResource('cachemanager')
                ->getCache(Zle_Application_Resource_Translate::DEFAULT_CACHE_KEY);
        $this->assertEquals($cache, $translate->getCache());
    }

    public function testTranslatorIsDisabledWithFlagToFalse()
    {
        $options = array('cacheEnabled' => false);
        $resource = new Zle_Application_Resource_Translate();
        $resource->setBootstrap($this->bootstrap);
        $resource->setOptions(array_merge($this->resourceOptions, $options));
        $translate = $resource->init();
        $this->assertNull($translate->getCache());
    }

    public function testTranslatorUseGivenKeyInCacheManager()
    {
        $cacheKey = 'myKey';
        $options = array('cacheKey' => $cacheKey, 'cacheEnabled' => true);
        $cacheOptions = array(
            'resources' => array(
                'cachemanager' => array(
                    $cacheKey => array(
                        'frontend' => array(
                            'name' => 'Core',
                            'options' => array(
                                'lifetime' => 7200,
                                'automatic_serialization' => true
                            )
                        ),
                        'backend' => array(
                            'name' => 'File',
                            'options' => array(
                                'cache_dir' => '/tmp/'
                            )
                        )
                    )
                ),
            ),
        );
        $this->bootstrap->setOptions($cacheOptions);
        $resource = new Zle_Application_Resource_Translate();
        $resource->setBootstrap($this->bootstrap);
        $resource->setOptions(array_merge($this->resourceOptions, $options));
        $translate = $resource->init();
        $cache = $this->bootstrap->getResource('cachemanager')
                ->getCache($cacheKey);
        $this->assertEquals($cache, $translate->getCache());
    }
}
