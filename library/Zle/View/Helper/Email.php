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
 * Zle_View_Helper_Email
 *
 * @category Zle
 * @package  Zle_View
 * @author   Fabio Napoleoni <f.napoleoni@gmail.com>
 * @license  http://framework.zend.com/license/new-bsd New BSD License
 * @link     http://framework.zend.com/
 */
class Zle_View_Helper_Email extends Zend_View_Helper_Abstract
{

    /**
     * Print an email link in a view
     *
     * @param string  $address email to link
     * @param string  $text    Link text, if null address is shown
     * @param boolean $escape  set to true if the text parameter should be escaped
     * 
     * @return string
     */
    public function email($address, $text = '', $escape = true)
    {
        $href = "<a href=\"mailto:$address\">%s</a>";
        $text = !empty($text) ? $text : $address;
        $text = $escape ? $this->view->escape($text) : $text;
        return sprintf($href, $text);
    }
}
