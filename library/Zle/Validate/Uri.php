<?php

/**
 * Zend Library Extension
 *
 * PHP version 5
 *
 * @category Zle
 * @package  Zle_Validate
 * @author   Fabio Napoleoni <f.napoleoni@gmail.com>
 * @license  http://framework.zend.com/license/new-bsd New BSD License
 * @link     http://framework.zend.com/
 */

/**
 * Zle_Validate_Uri
 *
 * @category Zle
 * @package  Zle_Validate
 * @author   Fabio Napoleoni <f.napoleoni@gmail.com>
 * @license  http://framework.zend.com/license/new-bsd New BSD License
 * @link     http://framework.zend.com/
 */
class Zle_Validate_Uri extends Zend_Validate_Abstract
{
    /**
     * Const returned when uri is not valid
     */
    const MALFORMED_URL = 'urlMalformedUrl';

    /**
     * Array of errors messages
     * @var array
     */
    protected $_messageTemplates = array(
        self::MALFORMED_URL => 'The value "%value%" is not a valid url',
    );

    /**
     * Returns true if and only if $value meets the validation requirements
     *
     * If $value fails validation, then this method returns false, and
     * getMessages() will return an array of messages that explain why the
     * validation failed.
     *
     * @param mixed $value the value to validate
     *
     * @throws Zend_Valid_Exception If validation of $value is impossible
     * @return boolean
     */
    public function isValid($value)
    {
        $value = (string)$value;
        $this->_setValue($value);

        if (!Zend_Uri_Http::check($value)) {
            $this->_error(self::MALFORMED_URL);
            return false;
        }

        return true;
    }
}
