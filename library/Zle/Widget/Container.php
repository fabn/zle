<?php

/**
 * Zend Library Extension
 *
 * PHP version 5
 *
 * @category Zle
 * @package  Zle_Widget
 * @author   Fabio Napoleoni <f.napoleoni@gmail.com>
 * @license  http://framework.zend.com/license/new-bsd New BSD License
 * @link     http://framework.zend.com/
 */

/**
 * A simple container class for {@link Zle_Widget}s
 *
 * @category Zle
 * @package  Zle_Widget
 * @author   Fabio Napoleoni <f.napoleoni@gmail.com>
 * @license  http://framework.zend.com/license/new-bsd New BSD License
 * @link     http://framework.zend.com/
 */
class Zle_Widget_Container extends ArrayObject
{
    /**
     * Insert a widget respecting the position defined in its
     * order attribute
     *
     * @param Zle_Widget $widget the widget to insert
     *
     * @return void
     */
    public function insert($widget)
    {
        if (!is_null($widget->getOrder())) {
            $this->offsetSet($widget->getOrder(), $widget);
            // TODO sort widgets by order, should be optimized
            $this->ksort();
        } else {
            $this->append($widget);
        }
    }
}
