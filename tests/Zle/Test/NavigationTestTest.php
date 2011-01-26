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
 * NavigationTestTest
 *
 * @category Zle
 * @package  Zle_Test
 * @author   Fabio Napoleoni <f.napoleoni@gmail.com>
 * @license  http://framework.zend.com/license/new-bsd New BSD License
 * @link     http://framework.zend.com/
 */
class NavigationTestTest extends PHPUnit_Framework_TestCase
{
    public function testNavigationStructureTest()
    {
        $suite = new PHPUnit_Framework_TestSuite();
        $suite->addTestSuite('StructureNavigationTest');
        $suite->run($result = new PHPUnit_Framework_TestResult());
        $this->assertEquals(5, $result->count());
        $this->assertEquals(
            1, $result->skippedCount(),
            'testNavigationAccess should be skipped because no acl given'
        );
        $this->assertTrue($result->wasSuccessful());
    }

    public function testNavigationAccessTest()
    {
        $suite = new PHPUnit_Framework_TestSuite();
        $suite->addTestSuite('AccessNavigationTest');
        $suite->run($result = new PHPUnit_Framework_TestResult());
        $this->assertEquals(5, $result->count());
        $this->assertTrue($result->wasSuccessful());
    }

    public function testAccessTestsAreSkippedWhenNoAclIsGiven()
    {
        $suite = new PHPUnit_Framework_TestSuite();
        $suite->addTestSuite('AccessNavigationTestWithNoAcl');
        $suite->run($result = new PHPUnit_Framework_TestResult());
        $this->assertEquals(2, $result->count());
        $this->assertEquals(1, $result->skippedCount());
    }
}

class StructureNavigationTest extends Zle_Test_NavigationTest
{

    /**
     * Implements this method to give samples test case for testing
     * structure of navigation under test
     *
     * @return array
     */
    public function getAccessSampleData()
    {
        // return fake data for acl access test
        return array(array('page1', 'ad', 'dsaf'));
    }

    /**
     * Implements this method to give samples test case for testing
     * structure of navigation under test
     *
     * @return array
     */
    public function getStructureSampleData()
    {
        return array(
            array('home', 0, null, 'id'),
            array('Page 1'),
            array('Page 2', 2),
            array('Page 2.1', 0, 'Page 2'),
        );
    }

    /**
     * Return the navigation object under test
     *
     * @return Zend_Navigation
     */
    protected function getNavigation()
    {
        return new Zend_Navigation(
            array(
                 array(
                     'label' => 'Page 1',
                     'id' => 'home',
                     'uri' => '/'
                 ),
                 array(
                     'label' => 'Page 2',
                     'controller' => 'page2',
                     'pages' => array(
                         array(
                             'label' => 'Page 2.1',
                             'action' => 'page2_1',
                         ),
                         array(
                             'label' => 'Page 2.2',
                             'action' => 'page2_2',
                         )
                     )
                 )
            )
        );
    }
}

class AccessNavigationTest extends Zle_Test_NavigationTest
{
    /**
     * Override this method to use testNavigationAccess test
     *
     * @return Zend_Acl
     */
    protected function getNavigationAcl()
    {
        $acl = new Zend_Acl();
        $acl->addRole('guest');
        $acl->addRole('user');
        $acl->addResource('home');
        $acl->addResource('page2');
        $acl->allow(null, 'home');
        $acl->allow('user', 'page2');
        return $acl;
    }

    /**
     * Implements this method to give samples test case for testing
     * structure of navigation under test
     *
     * @return array
     */
    public function getAccessSampleData()
    {
        return array(
            array('Home', 'guest', true),
            array('Home', 'user', true),
            array('Page 2.1', 'guest', false),
            array('Page 2.1', 'user', true),
        );
    }

    /**
     * Implements this method to give samples test case for testing
     * structure of navigation under test
     *
     * @return array
     */
    public function getStructureSampleData()
    {
        return array(
            array('Home'),
        );
    }

    /**
     * Return the navigation object under test
     *
     * @return Zend_Navigation
     */
    protected function getNavigation()
    {
        return new Zend_Navigation(
            array(
                 array(
                     'label' => 'Home',
                     'uri' => '/',
                     'resource' => 'home',
                 ),
                 array(
                     'label' => 'Page 2',
                     'controller' => 'page2',
                     'resource' => 'page2',
                     'pages' => array(
                         array(
                             'label' => 'Page 2.1',
                             'action' => 'page2_1',
                         ),
                     )
                 )
            )
        );
    }
}

class AccessNavigationTestWithNoAcl extends Zle_Test_NavigationTest
{
    /**
     * Implements this method to give samples test case for testing
     * structure of navigation under test
     *
     * @return array
     */
    public function getAccessSampleData()
    {
        return array(
            array('page1', 'guest', true),
        );
    }

    /**
     * Return the navigation object under test
     *
     * @return Zend_Navigation
     */
    protected function getNavigation()
    {
        return new Zend_Navigation();
    }

    /**
     * Implements this method to give samples test case for testing
     * structure of navigation under test
     *
     * @return array
     */
    public function getStructureSampleData()
    {
        return array();
    }
}
