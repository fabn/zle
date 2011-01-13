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
 * Zle_Test_PHPUnit_Database_Doctrine
 *
 * @category Zle
 * @package  Zle_Test
 * @author   Fabio Napoleoni <f.napoleoni@gmail.com>
 * @license  http://framework.zend.com/license/new-bsd New BSD License
 * @link     http://framework.zend.com/
 */
abstract class Zle_Test_PHPUnit_Database_Doctrine
    extends Zle_Test_PHPUnit_Database_TestCase
{

    /**
     * @var $schemaName string
     */
    protected $schemaName = 'doctrine';

    /**
     * Return the connection provided by Doctrine connection manager
     *
     * @see PHPUnit_Extensions_Database_TestCase::getConnection()
     *
     * @return PHPUnit_Extensions_Database_DB_IDatabaseConnection
     */
    protected function getConnection()
    {
        $conn = Doctrine_Manager::getInstance()
                ->getConnection($this->schemaName);
        return $this->createDefaultDBConnection(
            $conn->getDbh(), $this->schemaName
        );
    }
}
