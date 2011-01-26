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
 * Italian FiscalCode validator
 *
 * @category Zle
 * @package  Zle_Validate
 * @author   Fabio Napoleoni <f.napoleoni@gmail.com>
 * @license  http://framework.zend.com/license/new-bsd New BSD License
 * @link     http://framework.zend.com/
 */
class Zle_Validate_FiscalCode extends Zend_Validate_Abstract
{

    /**
     * @var string const for array indexes
     */
    const INVALID_FISCAL_CODE = 'invalidFiscalCode';
    const WRONG_FORMAT = 'wrongFormatFiscalCode';

    /**
     * Array of errors messages
     * @var array
     */
    protected $_messageTemplates = array(
        self::WRONG_FORMAT => 'Fiscal Code must be of 16 alphanumeric characters',
        self::INVALID_FISCAL_CODE => 'The provided fiscal code is invalid',
    );

    /**
     * Table for control code values
     * @var array
     */
    private $_oddTable = array(
        '0' => 1, '1' => 0, '2' => 5, '3' => 7, '4' => 9, '5' => 13, '6' => 15,
        '7' => 17, '8' => 19, '9' => 21, 'A' => 1, 'B' => 0, 'C' => 5, 'D' => 7,
        'E' => 9, 'F' => 13, 'G' => 15, 'H' => 17, 'I' => 19, 'J' => 21, 'K' => 2,
        'L' => 4, 'M' => 18, 'N' => 20, 'O' => 11, 'P' => 3, 'Q' => '6', 'R' => 8,
        'S' => 12, 'T' => 14, 'U' => 16, 'V' => 10, 'W' => 22, 'X' => 25, 'Y' => 24,
        'Z' => 23
    );

    /**
     * Returns true if and only if $value meets the validation requirements
     *
     * If $value fails validation, then this method returns false, and
     * getMessages() will return an array of messages that explain why the
     * validation failed.
     *
     * @param mixed $value value to be validated
     *
     * @throws Zend_Valid_Exception If validation of $value is impossible
     *
     * @return boolean
     */
    public function isValid($value)
    {
        $value = strtoupper($value);
        if (!preg_match('/^[A-Z0-9]{16}$/', $value)) {
            $this->_error(self::WRONG_FORMAT);
            return false;
        }
        $sum = 0;
        for ($i = 1; $i <= 13; $i += 2) {
            $c = $value[$i];
            if ('0' <= $c && $c <= '9') {
                $sum += ord($c) - ord('0');
            } else {
                $sum += ord($c) - ord('A');
            }
        }
        for ($i = 0; $i <= 14; $i += 2) {
            $sum += $this->_oddTable[$value[$i]];
        }
        if (chr($sum % 26 + ord('A')) != $value[15]) {
            $this->_error(self::INVALID_FISCAL_CODE);
            return false;
        }
        return true;
    }
}
