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
 * RecordExistDoctrineTest
 *
 * @category Zle
 * @package  Zle_Test
 * @author   Fabio Napoleoni <f.napoleoni@gmail.com>
 * @license  http://framework.zend.com/license/new-bsd New BSD License
 * @link     http://framework.zend.com/
 */
class RecordExistDoctrineTest extends PHPUnit_Framework_TestCase
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
        // create tables for account model
        Doctrine_Core::createTablesFromModels(dirname(__FILE__) . "/_files/models/");
    }

    protected function tearDown()
    {
        // empty account table
        Doctrine_Core::getTable('Account')
                ->createQuery()->delete()->execute();
        // unregister doctrine namespace
        $this->classLoader->unregisterNamespace('Doctrine_');
    }

    /**
     * @param string $user  account username
     * @param string $email email string
     * @return void
     */
    protected function insertAccount($user, $email)
    {
        Doctrine_Core::getTable('Account')
                ->create(array('username' => $user, 'email' => $email))
                ->save();
    }

    /**
     * Test basic function of RecordExists (no exclusion)
     *
     * @return void
     */
    public function testBasicFindsRecord()
    {
        $this->insertAccount('user', 'email');
        $validator = new Zle_Validate_Db_RecordExistDoctrine(array('table' => 'Account', 'field' => 'username'));
        $this->assertTrue($validator->isValid('user'));
    }

    /**
     * Test basic function of RecordExists (no exclusion)
     *
     * @return void
     */
    public function testBasicFindsNoRecord()
    {
        $this->insertAccount('user', 'email');
        $validator = new Zle_Validate_Db_RecordExistDoctrine(array('table' => 'Account', 'field' => 'username'));
        $this->assertFalse($validator->isValid('nosuchvalue'));
    }

    /**
     * Test the exclusion function
     *
     * @return void
     */
    public function testExcludeWithArray()
    {
        $this->insertAccount('user3', 'email');
        $validator = new Zle_Validate_Db_RecordExistDoctrine(
            array(
                 'table' => 'Account',
                 'field' => 'username',
                 'exclude' => array('field' => 'id', 'value' => 1))
        );
        $this->assertTrue($validator->isValid('user3'));
    }

    /**
     * Test the exclusion function
     * with an array
     *
     * @return void
     */
    public function testExcludeWithArrayNoRecord()
    {
        $this->insertAccount('user4', 'email');
        $validator = new Zle_Validate_Db_RecordExistDoctrine(
            array(
                 'table' => 'Account',
                 'field' => 'username',
                 'exclude' => array('field' => 'id', 'value' => 1))
        );
        $this->assertFalse($validator->isValid('nosuchvalue'));
    }

    /**
     * Test the exclusion function
     * with a string
     *
     * @return void
     */
    public function testExcludeWithString()
    {
        $this->insertAccount('user2', 'email');
        $this->insertAccount('user3', 'email');
        $validator = new Zle_Validate_Db_RecordExistDoctrine(
            array(
                 'table' => 'Account',
                 'field' => 'username',
                 'exclude' => 'id != 1',
            )
        );
        $this->assertTrue($validator->isValid('user3'));
    }

    /**
     * Test the exclusion function
     * with a string
     *
     * @return void
     */
    public function testExcludeWithStringNoRecord()
    {
        $validator = new Zle_Validate_Db_RecordExistDoctrine(
            'Account', 'username', 'id != 1'
        );
        $this->assertFalse($validator->isValid('nosuchvalue'));
    }
}
