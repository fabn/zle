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
     * Default area used for widgets
     */
    const DEFAULT_AREA = 'Zle_Widget';

    /**
     * @var array widget areas
     */
    protected static $areas = array();

    /**
     * Return an area for widgets
     *
     * @param string $name area name
     *
     * @return Zle_Widget_Container requested area
     */
    public static function getArea($name = self::DEFAULT_AREA)
    {
        if (!isset(self::$areas[$name])) {
            self::$areas[$name] = new self();
        }
        return self::$areas[$name];
    }

    /**
     * Unset the given area
     *
     * @param string $name area name
     *
     * @return void
     */
    public static function resetArea($name = self::DEFAULT_AREA)
    {
        unset(self::$areas[$name]);
    }

    /**
     * Reset all areas
     *
     * @return void
     */
    public static function resetAllAreas()
    {
        self::$areas = array();
    }


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
