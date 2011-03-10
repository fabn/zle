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
 * DatePickerTest
 *
 * @category Zle
 * @package  Zle_Test
 * @author   Fabio Napoleoni <f.napoleoni@gmail.com>
 * @license  http://framework.zend.com/license/new-bsd New BSD License
 * @link     http://framework.zend.com/
 */
class DatePickerTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Zle_Form_Element_DatePicker
     */
    private $_picker;

    /**
     * @var Zend_View
     */
    private $_view;

    /**
     * @var array sample options for date picker
     */
    private $_actualOptions = array(
        'imgOnly' => true,
        'showImg' => '/img/cal.gif',
    );

    protected function setUp()
    {
        $this->_picker = new Zle_Form_Element_DatePicker('foo');
        $this->_view = new Zend_View();
        $this->_view->addHelperPath(
            realpath(dirname(__FILE__) . '/../../../../library/Zle/View/Helper'),
            'Zle_View_Helper'
        );
        ZendX_JQuery::enableView($this->_view);
    }

    protected function tearDown()
    {
        // clear registry for locale testing
        Zend_Registry::_unsetInstance();
    }

    public function testDatePickerCanBeRendered()
    {
        $this->assertContains(
            'input type="text"', $this->_picker->render($this->_view),
            'DatePicker should be rendered as text element'
        );
    }

    public function testDatePickerAcceptOptionsUsingConstructor()
    {
        $options = array('datePickerOptions' => $this->_actualOptions);
        $picker = new Zle_Form_Element_DatePicker('foo', $options);
        $this->assertOptionIsSet('imgOnly', $this->_actualOptions['imgOnly'], $picker);
        $this->assertAttributeIsNotSet('imgOnly', $picker);
    }

    public function testDatePickerAcceptOptionsUsingSetter()
    {
        $picker = new Zle_Form_Element_DatePicker('foo');
        $picker->setDatePickerOptions($this->_actualOptions);
        $this->assertOptionIsSet('imgOnly', $this->_actualOptions['imgOnly'], $picker);
        $this->assertAttributeIsNotSet('imgOnly', $picker);
    }

    public function testDatePickerOptionsAreNotRenderedAsAttributes()
    {
        $this->_picker->setDatePickerOptions($this->_actualOptions);
        $this->assertNotContains('imgOnly', $this->_picker->render($this->_view));
    }

    public function testDatePickerOptionsAreRenderedInJavascript()
    {
        $options = array('foo' => uniqid());
        $this->_picker->setDatePickerOptions($options);
        // render the element
        $this->_picker->render($this->_view);
        /** @var $jq ZendX_JQuery_View_Helper_JQuery_Container */
        $jq = $this->_view->getHelper('jQuery');
        // test for date picker customization
        $this->assertContains(
            "$('#{$this->_picker->getId()}').datepicker",
            implode('', $jq->getOnLoadActions()), // concatenate onLoad actions
            'DatePicker should be initialized by id'
        );
        $this->assertContains(
            Zend_Json::encode($options),
            implode('', $jq->getOnLoadActions()), // concatenate onLoad actions
            'DatePicker should be initialized with custom options'
        );
    }

    /**
     * Ensure $option option is set
     *
     * @param string $option
     * @param mixed  $value
     * @param Zle_Form_Element_DatePicker $picker
     *
     * @return void
     */
    protected function assertOptionIsSet($option, $value, $picker = null) {
        if ($picker == null) {
            $picker = $this->_picker;
        }
        $this->assertArrayHasKey($option, $picker->options);
        $this->assertSame($value, $picker->options[$option]);
    }

    /**
     * Ensure $option attribute is not set
     *
     * @param string $option
     * @param Zle_Form_Element_DatePicker $picker
     *
     * @return void
     */
    protected function assertAttributeIsNotSet($option, $picker = null) {
        if ($picker == null) {
            $picker = $this->_picker;
        }
        $this->assertArrayNotHasKey($option, $picker->getAttribs());
    }
}
