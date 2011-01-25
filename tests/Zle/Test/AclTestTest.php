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
 * AclTestTest
 *
 * @category Zle
 * @package  Zle_Test
 * @author   Fabio Napoleoni <f.napoleoni@gmail.com>
 * @license  http://framework.zend.com/license/new-bsd New BSD License
 * @link     http://framework.zend.com/
 */
class AclTestTest extends PHPUnit_Framework_TestCase
{
    public function testAclIsTested()
    {
        $suite = new PHPUnit_Framework_TestSuite();
        $suite->addTestSuite('FooAclTest');
        $suite->run($result = new PHPUnit_Framework_TestResult());
        $this->assertTrue($result->wasSuccessful(), 'Test should be run successfully');
        $this->assertEquals(2, $result->count());
    }
}

class FooAclTest extends Zle_Test_AclTest
{
    /**
     * Return the acl under test
     *
     * @return Zend_Acl
     */
    protected function getAcl()
    {
        $acl = new Zend_Acl();
        $acl->addRole(new Zend_Acl_Role('guest'));
        $acl->addRole(new Zend_Acl_Role('user'));
        $acl->addResource('foo');
        $acl->allow('user', 'foo');
        return $acl;
    }

    /**
     * Implements this method to give samples test case for acl under test
     *
     * @return array
     */
    public function getSampleData()
    {
        return array(
            array(true, 'user', 'foo'),
            array(false, 'guest', 'foo'),
        );
    }
}
