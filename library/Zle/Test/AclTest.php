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
 * Zle_Test_AclTest
 *
 * @category Zle
 * @package  Zle_Test
 * @author   Fabio Napoleoni <f.napoleoni@gmail.com>
 * @license  http://framework.zend.com/license/new-bsd New BSD License
 * @link     http://framework.zend.com/
 */
abstract class Zle_Test_AclTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Zend_Acl
     */
    private $_acl;

    /**
     * Instantiate Acl using the abstract method provided
     *
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();
        $this->_acl = $this->getAcl();
    }

    /**
     * Return the acl under test
     *
     * @return Zend_Acl
     */
    abstract protected function getAcl();

    /**
     * Implements this method to give samples test case for acl under test
     *
     * @return array
     */
    abstract protected function getSampleData();

    /**
     * Test acl rules using data coming from the getSampleData method
     *
     * @param bool                               $expected  expected assertion result
     * @param string|Zend_Acl_Role_Interface     $role      role to test
     * @param string|Zend_Acl_Resource_Interface $resource  resource to test
     * @param string                             $privilege privilege to test
     *
     * @dataProvider getSampleData
     *
     * @return void
     */
    public function testAclRule($expected, $role, $resource = null, $privilege = null)
    {
        $this->assertSame(
            $expected,
            $this->_acl->isAllowed($role, $resource, $privilege),
            "Rule for $role:$resource:$privilege doesn't match"
        );
    }
}
