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
 * FormTest
 *
 * @category Zle
 * @package  Zle_Test
 * @author   Fabio Napoleoni <f.napoleoni@gmail.com>
 * @license  http://framework.zend.com/license/new-bsd New BSD License
 * @link     http://framework.zend.com/
 */
class FormTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Zle_Form
     */
    private $_form;

    protected function setUp()
    {
        Zend_Session::$_unitTestEnabled = true;
        // init a mock object
        $this->_form = $this->getMock('Zle_Form', array('initComponents'));
    }

    public function testTitleCanBySetUsingTheConstructor()
    {
        $testTitle = 'Foo';
        $form = $this->getMock(
            'Zle_Form', array('initComponents'),
            array(array('title' => $testTitle))
        ); /* @var $form Zle_Form */
        $this->assertEquals($testTitle, $form->getTitle());
    }

    public function testFormIsPostOnDefault()
    {
        $this->assertEquals(Zend_Form::METHOD_POST, $this->_form->getMethod());
    }

    public function testFormHasConfirmCode()
    {
        $hashName = get_class($this->_form) . '_confirmcode';
        $element = $this->_form->getElement($hashName);
        $this->assertNotNull($element, 'Confirm code should be set');
        $this->assertArrayHasKey(
            'Zend_Form_Decorator_ViewHelper', $element->getDecorators(),
            'Confirm code should have the ViewHelper decorator'
        );
        $this->assertEquals(
            1, count($element->getDecorators()),
            'confirm should have only the view helper decorator'
        );
        $this->assertTrue($element->getIgnore(), 'confirm code should be ignored');
    }

    public function testAddHiddenIsWorking()
    {
        $hiddenName = 'foo';
        $hiddenValue = 'bar';
        $this->_form->addHidden($hiddenName, $hiddenValue);
        $hidden = $this->_form->getElement($hiddenName);
        $this->assertNotNull($hidden, 'Hidden field should be added');
        $this->assertEquals($hiddenValue, $hidden->getValue());
        $this->assertArrayHasKey(
            'Zend_Form_Decorator_ViewHelper', $hidden->getDecorators(),
            'Confirm code should have the ViewHelper decorator'
        );
        $this->assertEquals(
            1, count($hidden->getDecorators()),
            'confirm should have only the view helper decorator'
        );
    }

    public function testPluginPathsAreSet()
    {
        $form = new Form_Test();
        $paths = $form->getPluginLoader(Zend_Form::ELEMENT)->getPaths();
        $this->assertArrayHasKey(
            'Test_Form_Element_', $paths,
            'Paths should contain Test namespace'
        );
    }
}

/**
 * Class used for testing
 *
 * @see FormTest::testPluginPathsAreSet
 */
class Form_Test extends Zle_Form
{
    protected function initComponents()
    {
    }

    protected function getNamespace()
    {
        return 'Test';
    }
}
