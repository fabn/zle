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
class ZendTest extends PHPUnit_Framework_TestCase
{
    public function testAdapterCanBeChangedUsingSetter()
    {
        $a = new Zend_Db_Adapter_Pdo_Sqlite(array('dbname' => tmpfile()));
        $t = new EmptyZendDbTest();
        $t->setAdapter($a);
        $this->assertEquals($a, $t->getAdapter(), 'Adapter should be changed');
    }

    public function testUnitTestIsInstantiable()
    {
        Zend_Db_Table_Abstract::setDefaultAdapter(
            new Zend_Db_Adapter_Pdo_Sqlite(array('dbname' => tmpfile()))
        );
        $suite = new PHPUnit_Framework_TestSuite();
        $suite->addTestSuite('EmptyZendDbTest');
        $suite->run($result = new PHPUnit_Framework_TestResult());
        $this->assertEquals(1, $result->count());
        $this->assertTrue($result->wasSuccessful());
    }

    public function testUnitTestLoadFixtures()
    {
        Zend_Db_Table_Abstract::setDefaultAdapter(
            $db = new Zend_Db_Adapter_Pdo_Sqlite(array('dbname' => tmpfile()))
        );
        $db->getConnection()->exec('DROP TABLE IF EXISTS `table`; CREATE TABLE `table` (`user` VARCHAR, `login` VARCHAR);');
        $suite = new PHPUnit_Framework_TestSuite();
        $suite->addTestSuite('NotEmptyZendDbTest');
        $suite->run($result = new PHPUnit_Framework_TestResult());
        $this->assertEquals(1, $result->count());
        $this->assertTrue($result->wasSuccessful());
    }
}


class EmptyZendDbTest extends Zle_Test_PHPUnit_Database_Zend
{
    public function testTheTruth()
    {
        $this->assertTrue(true);
    }
}

class NotEmptyZendDbTest extends Zle_Test_PHPUnit_Database_Zend
{
    protected $_fixtures = array('table.xml');

    public function testTwoRowsAreCreatedByFixtures()
    {
        $db = Zend_Db_Table_Abstract::getDefaultAdapter();
        $result = $db->fetchAll('SELECT * FROM `table`');
        $this->assertEquals(2, count($result));
    }

    protected function getFixturesPath()
    {
        return realpath(__DIR__ . '/_files');
    }
}
