<?php

/**
 * Zend Library Extension
 *
 * PHP version 5
 *
 * @category Zle
 * @package  Zle_
 * @author   Fabio Napoleoni <f.napoleoni@gmail.com>
 * @license  http://framework.zend.com/license/new-bsd New BSD License
 * @link     http://framework.zend.com/
 */

/**
 * RecordExistDoctrine
 *
 * @category Zle
 * @package  Zle_Validate
 * @author   Fabio Napoleoni <f.napoleoni@gmail.com>
 * @license  http://framework.zend.com/license/new-bsd New BSD License
 * @link     http://framework.zend.com/
 */
class Zle_Validate_Db_RecordExistDoctrine extends Zle_Validate_Db_Abstract
{

    /**
     * Returns true if and only if $value meets the validation requirements
     *
     * @param mixed $value the value to be tested
     *
     * @return boolean
     */
    public function isValid($value)
    {
        $valid = true;
        $this->_setValue($value);

        $result = $this->_query($value);
        if (!$result) {
            $valid = false;
            $this->_error(self::ERROR_NO_RECORD_FOUND);
        }

        return $valid;
    }
}
