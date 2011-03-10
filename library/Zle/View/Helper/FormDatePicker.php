<?php

/**
 * Zend Library Extension
 *
 * PHP version 5
 *
 * @category Zle
 * @package  Zle_View
 * @author   Fabio Napoleoni <f.napoleoni@gmail.com>
 * @license  http://framework.zend.com/license/new-bsd New BSD License
 * @link     http://framework.zend.com/
 */

/**
 * FormDatePicker
 *
 * @category Zle
 * @package  Zle_View
 * @author   Fabio Napoleoni <f.napoleoni@gmail.com>
 * @license  http://framework.zend.com/license/new-bsd New BSD License
 * @link     http://framework.zend.com/
 */
class Zle_View_Helper_FormDatePicker extends Zend_View_Helper_FormText
{
    /**
     * Class name for generated element
     */
    const DATE_PICKER_CLASS = 'zle-date-picker';

    /**
     * @var bool
     */
    private $_isJQueryLoaded = false;

    /**
     * JQuery DatePicker default options
     * @var array
     */
    protected static $datePickerDefaultOptions = array(
        'showOn' => 'both',
        'dateFormat' => 'yyyy-mm-dd',
    );

    /**
     * Load jquery framework
     *
     * @return void
     */
    public function loadJQuery()
    {
        if (!$this->_isJQueryLoaded) {
            /** @var $jq ZendX_JQuery_View_Helper_JQuery_Container */
            $jq = $this->view->jQuery();
            // enable jquery
            $jq->enable()->uiEnable();
            // if locale provided add localization to the date picker
            if (Zend_Registry::isRegistered('Zend_Locale')) {
                /** @var $locale Zend_Locale */
                $locale = Zend_Registry::get('Zend_Locale');
                // load datePicker localization
                $jq->addJavascriptFile(
                    Zle_View_Helper_JQueryPathBuilder::getInstance()->
                    getDatePickerLocaleJavascriptFile($locale, $jq)
                );
            }
            // set default options
            $loadJs = sprintf(
                "$.datepicker.setDefaults(%s);",
                Zend_Json::encode(self::$datePickerDefaultOptions)
            );
            $this->view->jQuery()->addOnLoad($loadJs);
            // add datepicker
            $this->view->jQuery()->addOnLoad(
                sprintf("$('.%s').datepicker();", self::DATE_PICKER_CLASS)
            );
            // load initialization code only once
            $this->_isJQueryLoaded = true;
        }
    }

    /**
     * Generates a 'datepicker' element.
     *
     * @param string|array $name    If a string, the element name.  If an
     * array, all other parameters are ignored, and the array elements
     * are used in place of added parameters.
     * @param mixed        $value   The element value.
     * @param array        $attribs Attributes for the element tag.
     *
     * @return string The element XHTML.
     */
    public function formDatePicker($name, $value = null, $attribs = null, $options = array())
    {
        // load jquery environment
        $this->loadJQuery();
        // TODO add support for custom options, use the 4th parameter of the helper
        if (!empty($options)) {
            $js = sprintf("$('#%s').datepicker('option', %s);",
                    $attribs['id'], Zend_Json::encode($options)
            );
            $this->view->jQuery()->addOnLoad($js);
        }
        // return a text element
        return parent::formText($name, $value, $attribs);
    }
}
