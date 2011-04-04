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
 * ZendTest
 *
 * @category Zle
 * @package  Zle_Test
 * @author   Fabio Napoleoni <f.napoleoni@gmail.com>
 * @license  http://framework.zend.com/license/new-bsd New BSD License
 * @link     http://framework.zend.com/
 */
class DoctrineTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Zend_Loader_Autoloader
     */
    protected $classLoader;

    protected function setUp()
    {
        $this->classLoader = Zend_Loader_Autoloader::getInstance();
        $this->classLoader->registerNamespace('Doctrine_');
        if (!class_exists('Doctrine_Manager')) {
            $this->markTestSkipped('Doctrine 1.2.x installation not found in include_path');
        }
        // create in memory connection
        Doctrine_Manager::connection("sqlite::memory:", 'doctrine');
    }

    protected function tearDown()
    {
        if (class_exists('Doctrine_Core')) {
            // close sqlite connection
            Doctrine_Manager::connection()->close();
        }
        // unregister doctrine namespace
        $this->classLoader->unregisterNamespace('Doctrine_');
    }


    public function testUnitTestIsInstantiable()
    {
        $suite = new PHPUnit_Framework_TestSuite();
        $suite->addTestSuite('EmptyDoctrineDbTest');
        $suite->run($result = new PHPUnit_Framework_TestResult());
        $this->assertEquals(1, $result->count());
        $this->assertTestsAreSuccessful($result);
    }

    public function testUnitTestLoadFixtures()
    {
        $db = Doctrine_Manager::connection();
        $db->exec('DROP TABLE IF EXISTS `foo`; CREATE TABLE `foo` (`a` VARCHAR, `b` VARCHAR);');
        $suite = new PHPUnit_Framework_TestSuite();
        $suite->addTestSuite('NotEmptyDoctrineDbTest');
        $suite->run($result = new PHPUnit_Framework_TestResult());
        $this->assertEquals(1, $result->count());
        $this->assertTestsAreSuccessful($result);
    }

    protected function assertTestsAreSuccessful(PHPUnit_Framework_TestResult $result)
    {
        if ($result->wasSuccessful()) {
            return;
        }
        $msg = "Test should pass, instead there are {$result->errorCount()} errors:\n";
        /** @var $error PHPUnit_Framework_TestFailure */
        foreach ($result->errors() as $error) {
            $msg .= $error->exceptionMessage();
        }
        $this->fail($msg);
    }
}


class EmptyDoctrineDbTest extends Zle_Test_PHPUnit_Database_Doctrine
{
    public function testTheTruth()
    {
        $this->assertTrue(true);
    }
}

class NotEmptyDoctrineDbTest extends Zle_Test_PHPUnit_Database_Doctrine
{
    protected $_fixtures = array('table.xml');

    public function testTwoRowsAreCreatedByFixtures()
    {
        $db = Doctrine_Manager::connection();
        $result = $db->fetchAll('SELECT * FROM `foo`');
        $this->assertEquals(2, count($result));
    }

    protected function getFixturesPath()
    {
        return realpath(__DIR__ . '/_files');
    }
}
