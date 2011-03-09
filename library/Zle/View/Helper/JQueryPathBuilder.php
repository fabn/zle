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
 * JQueryPathBuilder
 *
 * @category Zle
 * @package  Zle_View
 * @author   Fabio Napoleoni <f.napoleoni@gmail.com>
 * @license  http://framework.zend.com/license/new-bsd New BSD License
 * @link     http://framework.zend.com/
 */
class Zle_View_Helper_JQueryPathBuilder
    extends ZendX_JQuery_View_Helper_JQuery_Container
{

    /**
     * @var Zle_View_Helper_JQueryPathBuilder
     */
    private static $_instance;

    /**
     * Return an instance to the object
     *
     * @return Zle_View_Helper_JQueryPathBuilder
     */
    public static function getInstance()
    {
        if (null == self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Return the javascript file used to localize the datePicker component
     *
     * @param Zend_Locale                               $locale   the given locale
     * @param ZendX_JQuery_View_Helper_JQuery_Container $instance the jquery helper
     *
     * @return string
     */
    public function getDatePickerLocaleJavascriptFile(Zend_Locale $locale, $instance)
    {
        if ($instance->useUiCdn()) {
            $baseUri = $instance->_getJQueryLibraryBaseCdnUri();
            $uiPath = $baseUri .
                      ZendX_JQuery::CDN_SUBFOLDER_JQUERYUI .
                      $instance->getUiVersion() .
                      "/i18n/jquery.ui.datepicker-{$locale->getLanguage()}.js";
        } else if ($instance->useUiLocal()) {
            $uiPath = $instance->getUiPath() .
                      "/i18n/jquery.ui.datepicker-{$locale->getLanguage()}.js";
        }
        return $uiPath;
    }
}
