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
 * WidgetArea helper, used as placeholder for widgets. Use it in this way
 *
 * To configure widgets
 * $this->widgetArea()->append($widget)
 * $this->widgetArea('leftSidebar')->append($widget);
 *
 * Then in your layout script:
 * <?=$this->widgetArea('leftSidebar')?>
 * <?=$this->widgetArea('rightSidebar')?>
 *
 * @category Zle
 * @package  Zle_View
 * @author   Fabio Napoleoni <f.napoleoni@gmail.com>
 * @license  http://framework.zend.com/license/new-bsd New BSD License
 * @link     http://framework.zend.com/
 */
class Zle_View_Helper_WidgetArea extends Zend_View_Helper_Abstract
{
    /**
     * @var string currently configured widget area
     */
    protected $configuredArea = Zle_Widget_Container::DEFAULT_AREA;

    /**
     * Return the helper to configure it
     *
     * @param string $area area identifier
     *
     * @return Zle_View_Helper_WidgetArea fluent interface
     */
    public function widgetArea($area = Zle_Widget_Container::DEFAULT_AREA)
    {
        return $this->setConfiguredArea((string)$area);
    }

    /**
     * Return the configured area
     *
     * @param string $area area identifier
     *
     * @return Zle_Widget_Container a reference to the requested area
     */
    public function getArea($area = '')
    {
        if (empty($area)) {
            $area = $this->getConfiguredArea();
        }
        return Zle_Widget_Container::getArea($area);
    }

    /**
     * Insert the given widget in the currently configured area
     *
     * @param int|string       $position widget position
     * @param array|Zle_Widget $spec     widget to insert as instance or spec
     *
     * @return Zle_View_Helper_WidgetArea fluent interface
     */
    public function insert($position, $spec)
    {
        // build a widget
        $widget = $this->getWidgetFromSpec($spec);
        // force its order
        $widget->setOrder($position);
        // insert it into the configured container
        $this->getArea()->insert($widget);
    }

    /**
     * Append the given widget in the currently configured area
     *
     * @param array|Zle_Widget $spec widget to insert as instance or spec
     *
     * @return Zle_View_Helper_WidgetArea fluent interface
     */
    public function append($spec)
    {
        // append the widget to the configured area
        $this->getArea()->append($this->getWidgetFromSpec($spec));
        // fluent interface
        return $this;
    }

    /**
     * Set all widget for the given areas, it doesn't use configured area
     *
     * @param array $widgets and array of
     *
     * @return Zle_View_Helper_WidgetArea fluent interface
     */
    public function set(array $widgets)
    {
        foreach ($widgets as $areaName => $areaContent) {
            /** @var $areaContent array */
            $area = $this->getArea($areaName);
            foreach ($areaContent as $widgetSpec) {
                $area->append($this->getWidgetFromSpec($widgetSpec));
            }
        }
        return $this;
    }

    /**
     * Return output for currently configured area
     *
     * @return string
     */
    public function render()
    {
        return $this->getArea()->render();
    }

    /**
     * Return output for the currently configured area
     *
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }

    /**
     * Configured area getter
     *
     * @param string $configuredArea area name
     *
     * @return Zle_View_Helper_WidgetArea fluent interface
     */
    protected function setConfiguredArea($configuredArea)
    {
        $this->configuredArea = $configuredArea;
        return $this;
    }

    /**
     * Configured area setter
     *
     * @return string
     */
    protected function getConfiguredArea()
    {
        return $this->configuredArea;
    }

    /**
     * Return a widget
     *
     * @param array|Zle_Widget $spec widget to insert as instance or spec
     *
     * @return Zle_Widget
     */
    protected function getWidgetFromSpec($spec)
    {
        if (is_array($spec)) {
            $spec = Zle_Widget::factory($spec);
        }
        if (!$spec instanceof Zle_Widget) {
            throw new Zle_Exception(
                sprintf(
                    "\$spec should be instance of Zle_Widget or array, %s given", get_class($spec)
                )
            );
        }
        return $spec;
    }
}
