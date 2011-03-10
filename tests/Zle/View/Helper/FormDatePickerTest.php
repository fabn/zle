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
 * FormDatePickerTest
 *
 * @category Zle
 * @package  Zle_Test
 * @author   Fabio Napoleoni <f.napoleoni@gmail.com>
 * @license  http://framework.zend.com/license/new-bsd New BSD License
 * @link     http://framework.zend.com/
 */
class FormDatePickerTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Zle_View_Helper_FormDatePicker
     */
    private $_helper;

    /**
     * @var Zend_View
     */
    private $_view;

    /**
     * @var array
     */
    private $_helperOptions;

    protected function setUp()
    {
        $this->_helper = new Zle_View_Helper_FormDatePicker();
        $this->_helper->setView($this->_view = new Zend_View());
        ZendX_JQuery::enableView($this->_helper->view);
        $this->_helperOptions = Zle_View_Helper_FormDatePicker::getDatePickerDefaultOptions();
    }

    protected function tearDown()
    {
        // clear registry for locale testing
        Zend_Registry::_unsetInstance();
        // restore default options value
        Zle_View_Helper_FormDatePicker::setDatePickerDefaultOptions($this->_helperOptions);
    }

    public function testJQueryIsEnabledAfterCall()
    {
        $this->_helper->formDatePicker('calendar');
        /** @var $jq ZendX_JQuery_View_Helper_JQuery_Container */
        $jq = $this->_helper->view->jQuery();
        $this->assertTrue($jq->isEnabled(), 'jQuery should be enabled');
        $this->assertTrue($jq->uiIsEnabled(), 'jQuery UI should be enabled');

    }

    public function testDatePickersDefaultsAreSet()
    {
        $this->_helper->formDatePicker('calendar');
        /** @var $jq ZendX_JQuery_View_Helper_JQuery_Container */
        $jq = $this->_helper->view->jQuery();
        $this->assertContains(
            '$.datepicker.setDefaults(',
            implode('', $jq->getOnLoadActions()), // concatenate onLoad actions
            'DatePicker defaults should be set'
        );
    }

    public function testLocaleIsLoadedWhenRegistryKeyIsSet()
    {
        // set locale
        Zend_Registry::set('Zend_Locale', new Zend_Locale('it'));
        // call helper
        $this->_helper->formDatePicker('calendar');
        /** @var $jq ZendX_JQuery_View_Helper_JQuery_Container */
        $jq = $this->_helper->view->jQuery();
        $this->assertContains(
            'i18n/jquery.ui.datepicker-it.js',
            implode('', $jq->getJavascriptFiles()), // concatenate javascript files
            'Localization should be set'
        );
    }

    public function testLocaleIsLoadedWithoutCdn()
    {
        // set locale
        Zend_Registry::set('Zend_Locale', new Zend_Locale('it'));
        /** @var $jq ZendX_JQuery_View_Helper_JQuery_Container */
        $jq = $this->_helper->view->jQuery();
        $jq->setUiLocalPath('/jquery-root');
        // call helper
        $this->_helper->formDatePicker('calendar');
        $this->assertNotContains(
            substr(ZendX_JQuery::CDN_BASE_GOOGLE, 7),
            implode('', $jq->getJavascriptFiles()), // concatenate javascript files
            'Localization should be set'
        );
    }

    public function testVersionIsUsedInLocalization()
    {
        // set locale
        Zend_Registry::set('Zend_Locale', new Zend_Locale('it'));
        /** @var $jq ZendX_JQuery_View_Helper_JQuery_Container */
        $jq = $this->_helper->view->jQuery()->setUiVersion('1.8.1');
        $this->_helper->formDatePicker('calendar');
        $this->assertContains(
            '1.8.1',
            implode('', $jq->getJavascriptFiles()), // concatenate javascript files
            'Ui version should be used'
        );
    }

    public function testDatePickerInitializationIsDone()
    {
        $this->_helper->formDatePicker('calendar');
        /** @var $jq ZendX_JQuery_View_Helper_JQuery_Container */
        $jq = $this->_helper->view->jQuery();
        $this->assertContains(
            Zle_View_Helper_FormDatePicker::DATE_PICKER_CLASS,
            implode('', $jq->getOnLoadActions()), // concatenate onLoad actions
            'DatePicker should be initialized'
        );
    }

    public function testDatePickerClassShouldBeSet()
    {
        $this->assertContains(
            sprintf('class="%s"', Zle_View_Helper_FormDatePicker::DATE_PICKER_CLASS),
            $this->_helper->formDatePicker('calendar')
        );
    }

    public function testDatePickerClassShouldBeAdded()
    {
        $this->assertRegExp(
            sprintf('/class=".*%s.*"/', Zle_View_Helper_FormDatePicker::DATE_PICKER_CLASS),
            $this->_helper->formDatePicker('calendar', null, array('class' => 'fooClass'))
        );
    }

    public function testDefaultOptionsCanBeChanged()
    {
        $newOptions = array('foo' => 'bar');
        Zle_View_Helper_FormDatePicker::setDatePickerDefaultOptions($newOptions);
        $this->assertSame(
            $newOptions,
            Zle_View_Helper_FormDatePicker::getDatePickerDefaultOptions()
        );
    }

}
