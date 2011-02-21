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
 * NoRecordExistDoctrineTest
 *
 * @category Zle
 * @package  Zle_Test
 * @author   Fabio Napoleoni <f.napoleoni@gmail.com>
 * @license  http://framework.zend.com/license/new-bsd New BSD License
 * @link     http://framework.zend.com/
 */
class NoRecordExistDoctrineTest extends PHPUnit_Framework_TestCase
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
        if (class_exists('Doctrine_Core')) {
            // empty account table
            Doctrine_Core::getTable('Account')
                    ->createQuery()->delete()->execute();
        }
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
        $this->insertAccount('value1', 'email');
        $validator = new Zle_Validate_Db_NoRecordExistDoctrine('Account', 'username');
        $this->assertFalse($validator->isValid('value1'));
    }

    /**
     * Test basic function of RecordExists (no exclusion)
     *
     * @return void
     */
    public function testBasicFindsNoRecord()
    {
        $validator = new Zle_Validate_Db_NoRecordExistDoctrine('Account', 'username');
        $this->assertTrue($validator->isValid('nosuchvalue'));
    }

    /**
     * Test the exclusion function
     *
     * @return void
     */
    public function testExcludeWithArray()
    {
        $this->insertAccount('value3', 'email');
        $validator = new Zle_Validate_Db_NoRecordExistDoctrine(
            'Account', 'username', array('field' => 'id', 'value' => 1)
        );
        $this->assertFalse($validator->isValid('value3'));
    }

    /**
     * Test the exclusion function
     * with an array
     *
     * @return void
     */
    public function testExcludeWithArrayNoRecord()
    {
        $validator = new Zle_Validate_Db_NoRecordExistDoctrine(
            'Account', 'username', array('field' => 'id', 'value' => 1)
        );
        $this->assertTrue($validator->isValid('nosuchvalue'));
    }

    /**
     * Test the exclusion function
     * with a string
     *
     * @return void
     */
    public function testExcludeWithString()
    {
        $this->insertAccount('value4', 'email');
        $validator = new Zle_Validate_Db_NoRecordExistDoctrine(
            'Account', 'username', 'id != 1'
        );
        $this->assertFalse($validator->isValid('value4'));
    }

    /**
     * Test the exclusion function
     * with a string
     *
     * @return void
     */
    public function testExcludeWithStringNoRecord()
    {
        $this->insertAccount('value5', 'email');
        $validator = new Zle_Validate_Db_NoRecordExistDoctrine(
            'Account', 'username', 'id != 1'
        );
        $this->assertTrue($validator->isValid('nosuchvalue'));
    }

}
