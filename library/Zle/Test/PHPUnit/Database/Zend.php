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
 * Zle_Test_PHPUnit_Database_Zend
 *
 * @category Zle
 * @package  Zle_Test
 * @author   Fabio Napoleoni <f.napoleoni@gmail.com>
 * @license  http://framework.zend.com/license/new-bsd New BSD License
 * @link     http://framework.zend.com/
 */
abstract class Zle_Test_PHPUnit_Database_Zend
    extends Zle_Test_PHPUnit_Database_TestCase
{
    /**
     * @var Zend_Db_Adapter_Abstract
     */
    private $_adapter;

    /**
     * Return the connection provided by Zend_Db default adapter
     *
     * @see PHPUnit_Extensions_Database_TestCase::getConnection()
     *
     * @return PHPUnit_Extensions_Database_DB_IDatabaseConnection
     */
    protected function getConnection()
    {
        return new Zend_Test_PHPUnit_Db_Connection(
            $this->getAdapter(), $this->schemaName
        );
    }

    /**
     * Set the adapter to use for testing
     *
     * @param Zend_Db_Adapter_Abstract $adapter the adapter to use in tests
     *
     * @return void
     */
    public function setAdapter($adapter)
    {
        $this->_adapter = $adapter;
    }

    /**
     * Adapter getter, returns Zend_Db_Table_Abstract::getDefaultAdapter()
     * if none provided using setter or overloading the method
     *
     * @return Zend_Db_Adapter_Abstract
     */
    public function getAdapter()
    {
        if (!$this->_adapter) {
            return Zend_Db_Table_Abstract::getDefaultAdapter();
        }
        return $this->_adapter;
    }

    /**
     * Change the default one until ZF-10483 is fixed
     *
     * @link http://framework.zend.com/issues/browse/ZF-10483
     *
     * @return PHPUnit_Extensions_Database_Operation_DatabaseOperation
     */
    protected function getSetUpOperation()
    {
        return new Zend_Test_PHPUnit_Db_Operation_Insert();
    }

    /**
     * Truncate the database after tests
     *
     * @return PHPUnit_Extensions_Database_Operation_DatabaseOperation
     */
    protected function getTearDownOperation()
    {
        return new Zend_Test_PHPUnit_Db_Operation_Truncate();
    }
}
