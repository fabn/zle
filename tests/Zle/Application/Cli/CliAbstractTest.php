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
 * CliAbstractTest
 *
 * @category Zle
 * @package  Zle_Test
 * @author   Fabio Napoleoni <f.napoleoni@gmail.com>
 * @license  http://framework.zend.com/license/new-bsd New BSD License
 * @link     http://framework.zend.com/
 */
class CliAbstractTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Zle_Application_Cli_CliAbstract
     */
    private $_cli;

    protected function setUp()
    {
        $this->_cli = $this->getMock(
            'Zle_Application_Cli_CliAbstract',
            array('invalidMethodNeededForConcreteMethods')
        );
    }

    public function testCliAbstractImplementsInterface()
    {
        $this->assertTrue($this->_cli instanceof Zle_Application_Cli_Cli);
    }

    public function testSubClassesMustOverrideRunMethod()
    {
        try {
            $this->_cli->run();
            $this->fail('Exception not raised');
        } catch (Zend_Application_Exception $e) {
            $this->assertContains('Override this method', $e->getMessage());
        }
    }

    public function testApplicationGetterAndSetter()
    {
        $application = new Zend_Application('testing');
        $this->assertSame(
            $this->_cli, $this->_cli->setApplication($application),
            'Should provide fluent interface'
        );
        $this->assertSame($application, $this->_cli->getApplication());
    }

    public function testOptionsSetter()
    {
        $this->assertSame(
            $this->_cli, $this->_cli->setOptions(new Zend_Console_Getopt(array())),
            'Should provide fluent interface'
        );
    }

    public function testHasDefaultOptionsIsTrueByDefault()
    {
        $this->assertTrue($this->_cli->hasDefaultOptions());
    }

    public function testHasDefaultOptionsToFalseDoesNotReturnDefaultOptions()
    {
        $app = $this->getMock('Zle_Application_Cli_CliAbstract', array('hasDefaultOptions'));
        $this->assertTrue(!$app->hasDefaultOptions());
        $this->assertSame(array(), $app->getOptions());
    }

    public function testGetOptionsIncludesHelpAndEnvironment()
    {
        $this->assertArrayHasKey('help|h', $this->_cli->getOptions());
    }
}
