<?php

/**
 * Zend Library Extension
 *
 * PHP version 5
 *
 * @category Zle
 * @package  Zle_Form
 * @author   Fabio Napoleoni <f.napoleoni@gmail.com>
 * @license  http://framework.zend.com/license/new-bsd New BSD License
 * @link     http://framework.zend.com/
 */

/**
 * DatePicker
 *
 * @category Zle
 * @package  Zle_Form
 * @author   Fabio Napoleoni <f.napoleoni@gmail.com>
 * @license  http://framework.zend.com/license/new-bsd New BSD License
 * @link     http://framework.zend.com/
 */
class Zle_Form_Element_DatePicker extends Zend_Form_Element_Text
{
    /**
     * Default form view helper to use for rendering
     * @var string
     */
    public $helper = 'formDatePicker';

    public $options = array();

    public function setDatePickerOptions(array $options = array()) {
        $this->options = $options;
    }
}
