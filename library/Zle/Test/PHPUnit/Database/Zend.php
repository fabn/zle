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
     * Return the connection provided by Zend_Db default adapter
     *
     * @see PHPUnit_Extensions_Database_TestCase::getConnection()
     *
     * @return PHPUnit_Extensions_Database_DB_IDatabaseConnection
     */
    protected function getConnection()
    {
        $defaultAdapter = Zend_Db_Table_Abstract::getDefaultAdapter();
        return $this->createZendDbConnection(
            $defaultAdapter, $this->schemaName
        );
    }
}
