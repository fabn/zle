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

    /**
     * @var array datePicker options
     */
    public $options = array();

    /**
     * Options setter, called direct or using constructor by giving
     * a datePickerOptions key in the config
     *
     * @param array $options specific options for this instance of datePicker
     *
     * @return void
     */
    public function setDatePickerOptions(array $options = array())
    {
        $this->options = $options;
    }
}
