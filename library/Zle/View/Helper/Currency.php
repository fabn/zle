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
 * Zle_View_Helper_Currency
 *
 * @category Zle
 * @package  Zle_View
 * @author   Fabio Napoleoni <f.napoleoni@gmail.com>
 * @license  http://framework.zend.com/license/new-bsd New BSD License
 * @link     http://framework.zend.com/
 */
class Zle_View_Helper_Currency extends Zend_View_Helper_Abstract
{
    /**
     * Format a numeric currency value and return it as a string
     *
     * @param int|float $value   any value that return true with is_numeric
     * @param array     $options additional options to pass to the currency
     *                           constructor
     * @param string    $locale  locale value
     *
     * @throws InvalidParameterException if the $value parameter is not numeric
     * @return string the formatted value
     */
    public function currency($value, $options = array(), $locale = null)
    {
        if (!is_numeric($value)) {
            throw new InvalidArgumentException(
                'Numeric argument expected ' . gettype($value) . ' given'
            );
        }
        $options = array_merge($options, array('value' => $value));
        $currency = new Zend_Currency($options, $locale);
        return $currency->toString();
    }
}
