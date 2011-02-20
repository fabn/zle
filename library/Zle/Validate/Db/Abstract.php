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
 * Zle_Validate_Db_Abstract uses the same logic of Zend_Validate_Db_Abstract, however
 * it does needs a working Doctrine 1.x connection
 *
 * @category Zle
 * @package  Zle_Validate
 * @author   Fabio Napoleoni <f.napoleoni@gmail.com>
 * @license  http://framework.zend.com/license/new-bsd New BSD License
 * @link     http://framework.zend.com/
 */
abstract class Zle_Validate_Db_Abstract extends Zend_Validate_Db_Abstract
{
    /**
     * Run query and returns matches, or null if no matches are found.
     *
     * @param  String $value
     * @return Array when matches are found.
     */
    protected function _query($value)
    {
        /**
         * Build select object
         */
        $query = Doctrine_Core::getTable($this->getTable())
                ->createQuery();
                //->select($this->getField());
        // select the given record
        $query->where(
            $query->getConnection()->quoteIdentifier($this->getField()) . ' = ?',
            $value
        );
        // check for exclude
        if ($this->_exclude !== null) {
            if (is_array($this->_exclude)) {
                $query->andWhere(
                    $query->getConnection()
                            ->quoteIdentifier($this->_exclude['field']) . ' != ?',
                    $this->_exclude['value']
                );
            } else {
                $query->andWhere($this->_exclude);
            }
        }

        /**
         * Run query
         */
        $result = $query->fetchOne();

        return $result;
    }
}
