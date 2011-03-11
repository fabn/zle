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
 * DoctrineProfilerFirebugTest
 *
 * @category Zle
 * @package  Zle_Test
 * @author   Fabio Napoleoni <f.napoleoni@gmail.com>
 * @license  http://framework.zend.com/license/new-bsd New BSD License
 * @link     http://framework.zend.com/
 */
class DoctrineProfilerFirebugTest extends PHPUnit_Framework_TestCase
{
    protected $_controller = null;
    protected $_request = null;
    protected $_response = null;
    protected $_profiler = null;
    protected $_plugin = null;
    protected $_db = null;

    public function setUp()
    {
        if (!extension_loaded('pdo_sqlite')) {
            $this->markTestSkipped('Requires PDO_Sqlite extension');
        }

        $this->_request = new Zend_Db_Profiler_FirebugTest_Request();
        $this->_response = new Zend_Db_Profiler_FirebugTest_Response();

        $channel = Zend_Wildfire_Channel_HttpHeaders::getInstance();
        $channel->setRequest($this->_request);
        $channel->setResponse($this->_response);
        // try to autoload doctrine
        Zend_Loader_Autoloader::getInstance()->registerNamespace('Doctrine_');
        if (!class_exists('Doctrine_Manager')) {
            $this->markTestSkipped('Doctrine 1.2.x installation not found in include_path');
        }
        // create in memory connection
        Doctrine_Manager::connection("sqlite::memory:", 'doctrine');
        // instantiate plugin
        $this->_plugin = new Zle_Controller_Plugin_DoctrineProfilerFirebug();
        // create tables for account model
        Doctrine_Core::createTablesFromModels(dirname(__FILE__) . "/_files/models/");
    }

    public function tearDown()
    {
        if (extension_loaded('pdo_sqlite') && class_exists('Doctrine_Core')) {
            Doctrine_Manager::connection()->getDbh()->exec('DROP TABLE accounts');
        }
        // unregister doctrine namespace
        Zend_Loader_Autoloader::getInstance()->unregisterNamespace('Doctrine_');
        Zend_Wildfire_Channel_HttpHeaders::destroyInstance();
        Zend_Wildfire_Plugin_FirePhp::destroyInstance();
    }

    public function testWithSimpleQuery()
    {
        $channel = Zend_Wildfire_Channel_HttpHeaders::getInstance();
        $protocol = $channel->getProtocol(Zend_Wildfire_Plugin_FirePhp::PROTOCOL_URI);

        Doctrine_Query::create()->from('Account')->execute();
        // call plugin postDispatch to record profiler data
        $this->_plugin->dispatchLoopShutdown();

        Zend_Wildfire_Channel_HttpHeaders::getInstance()->flush();

        $messages = $protocol->getMessages();

        $this->assertStringStartsWith(
            '[{"Type":"TABLE","Label":"Zle_Db_Profiler_Firebug (',
            $messages
            [Zend_Wildfire_Plugin_FirePhp::STRUCTURE_URI_FIREBUGCONSOLE]
            [Zend_Wildfire_Plugin_FirePhp::PLUGIN_URI][0]
        );
    }

    public function testNoQueries()
    {
        $channel = Zend_Wildfire_Channel_HttpHeaders::getInstance();
        $protocol = $channel->getProtocol(Zend_Wildfire_Plugin_FirePhp::PROTOCOL_URI);

        Zend_Wildfire_Channel_HttpHeaders::getInstance()->flush();

        // call plugin postDispatch to record profiler data
        $this->_plugin->dispatchLoopShutdown();

        $messages = $protocol->getMessages();

        $this->assertFalse($messages);
    }
}

class Zend_Db_Profiler_FirebugTest_Request extends Zend_Controller_Request_Http
{
    public function getHeader($header)
    {
        if ($header == 'User-Agent') {
            return 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X; en-US; rv:1.8.1.14) Gecko/20080404 Firefox/2.0.0.14 FirePHP/0.1.0';
        }
    }
}

class Zend_Db_Profiler_FirebugTest_Response extends Zend_Controller_Response_Http
{
    public function canSendHeaders($throw = false)
    {
        return true;
    }
}
