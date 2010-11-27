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
 * Date
 *
 * @category Zle
 * @package  Zle_View
 * @author   Fabio Napoleoni <f.napoleoni@gmail.com>
 * @license  http://framework.zend.com/license/new-bsd New BSD License
 * @link     http://framework.zend.com/
 */
class Zle_View_Helper_Date extends Zend_View_Helper_Abstract
{
    /**
     * Render a date into a view using the given format,
     * it can be a textual representation or a Zend_Date object
     *
     * @param string|Zend_Date $date         the date to format
     * @param string           $outputFormat output format for the date
     * @param string           $inputFormat  input format of the given date,
     *                                       used only if $date is a string
     *
     * @return string
     */
    public function date($date, $outputFormat = Zend_Date::DATE_LONG, $inputFormat = null)
    {
        $zdate = $date instanceof Zend_Date
                ? $date
                : new Zend_Date((string)$date, $inputFormat);
        // return formatted date as a string
        return $zdate->get($outputFormat);
    }
}
