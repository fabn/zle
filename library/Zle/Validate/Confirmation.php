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
 * Zle_Validate_Confirmation
 *
 * @category Zle
 * @package  Zle_Validate
 * @author   Fabio Napoleoni <f.napoleoni@gmail.com>
 * @license  http://framework.zend.com/license/new-bsd New BSD License
 * @link     http://framework.zend.com/
 */
class Zle_Validate_Confirmation extends Zend_Validate_Abstract
{
    /**
     * @var string const for array indexes
     */
    const NOT_MATCH = 'confirmationNotMatch';

    /**
     * Array of errors messages
     * @var array
     */
    protected $_messageTemplates = array(
        self::NOT_MATCH => 'Confirmation does not match');

    /**
     * @var string form element with the confirmation
     */
    protected $key;

    /**
     * Setup the validator
     *
     * @param string $key element which hold the confirm (default: password_confirm)
     *
     * @return void
     */
    public function __construct($key = 'password_confirm')
    {
        $this->key = $key;
    }

    /**
     * Returns true if and only if $value meets the validation requirements
     *
     * If $value fails validation, then this method returns false, and
     * getMessages() will return an array of messages that explain why the
     * validation failed.
     *
     * @param mixed $value   value to check validity for
     * @param array $context form context
     *
     * @throws Zend_Valid_Exception If validation of $value is impossible
     * @return boolean
     */
    public function isValid($value, $context = null)
    {
        $value = (string)$value;
        $this->_setValue($value);

        if (!is_array($context)) {
            throw new Zend_Validate_Exception('$context should be an array');
        }
        if (!isset($context[$this->key])) {
            throw new Zend_Validate_Exception(
                '$context does not have ' . $this->key
                . ' element given in the constructor'
            );
        }
        if ($value === $context[$this->key]) {
            return true;
        }
        $this->_error(self::NOT_MATCH);
        return false;
    }
}
