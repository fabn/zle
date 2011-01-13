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
 * Zle_Test_NavigationTest
 *
 * @category Zle
 * @package  Zle_Test
 * @author   Fabio Napoleoni <f.napoleoni@gmail.com>
 * @license  http://framework.zend.com/license/new-bsd New BSD License
 * @link     http://framework.zend.com/
 */
abstract class Zle_Test_NavigationTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Zend_Navigation
     */
    private $_navigation;

    /**
     * Instantiate Navigation using the abstract method provided
     *
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();
        $this->_navigation = $this->getNavigation();
    }

    /**
     * Override this method to use testNavigationAccess test
     *
     * @return null
     */
    protected function getNavigationAcl()
    {
        return null;
    }

    /**
     * Return the navigation object under test
     *
     * @return Zend_Navigation
     */
    abstract protected function getNavigation();

    /**
     * Implements this method to give samples test case for testing
     * structure of navigation under test
     *
     * @return array
     */
    abstract public function getStructureSampleData();

    /**
     * Implements this method to give samples test case for testing
     * structure of navigation under test
     *
     * @return array
     */
    abstract public function getAccessSampleData();

    /**
     * This method tests the structure of navigation container
     *
     * @param string $propertyValue the value of the property for finding page
     * @param int    $childPages    if given as a number method will test that the
     *                              page has exactly $childPages sons
     * @param string $parentLabel   if given method will test for presence of a
     *                              parent page with this label
     * @param string $propertyName  if given method will try to find the given page
     *                              by this property instead of by label
     *
     * @dataProvider getStructureSampleData
     *
     * @return void
     */
    public function testNavigationStructure(
        $propertyValue, $childPages = false, $parentLabel = null,
        $propertyName = 'label'
    ) {
        $page = $this->_navigation->findOneBy($propertyName, $propertyValue);
        $this->assertNotNull(
            $page,
            sprintf(
                "A page with %s set to %s should exists in navigation",
                ucfirst($propertyName),
                $propertyValue
            )
        );
        if (is_numeric($childPages)) {
            $this->assertEquals(
                $childPages,
                count($page->getPages()),
                "Page '{$page->label}' should have $childPages child pages"
            );
        }
        if ($parentLabel) {
            $this->assertType(
                'Zend_Navigation_Page', $page->getParent(),
                "Parent page should be a page instance"
            );
            $this->assertEquals(
                $parentLabel, $page->getParent()->label,
                "'{$page->label}' should be son of '{$parentLabel}'"
            );
        }
    }

    /**
     * This method tests the privileges required to see a given link
     *
     * @param string $propertyValue the value of the property for finding page
     * @param string $role          the role to test against
     * @param bool   $result        expected result
     * @param string $propertyName  if given method will try to find the given page
     *                              by this property instead of by label
     *
     * @dataProvider getAccessSampleData
     *
     * @return void
     */
    public function testNavigationAccess(
        $propertyValue, $role, $result, $propertyName = 'label'
    ) {
        $acl = $this->getNavigationAcl();
        if (!$acl) {
            $this->markTestSkipped(
                'You must override the getAcl method in order to use this test'
            );
        }
        $page = $this->_navigation->findBy($propertyName, $propertyValue);
        $this->assertNotNull(
            $page,
            sprintf(
                "A page with %s set to %s should exists in navigation",
                ucfirst($propertyName),
                $propertyValue
            )
        );
        $menu = new Zend_View_Helper_Navigation_Menu();
        $menu->setRole($role)->setAcl($acl);
        $this->assertSame(
            $result,
            $menu->accept($page),
            "Page with label {$page->label} is not accepted for role " .
            $role instanceof Zend_Acl_Role_Interface ? $role->getRoleId() : $role
        );
    }
}
