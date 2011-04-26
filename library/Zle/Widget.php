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
 * Zle_Widget
 *
 * @category Zle
 * @package  Zle_Widget
 * @author   Fabio Napoleoni <f.napoleoni@gmail.com>
 * @license  http://framework.zend.com/license/new-bsd New BSD License
 * @link     http://framework.zend.com/
 */
class Zle_Widget
{
    /**
     * @var string
     */
    protected $partial;

    /**
     * @var Zend_View_Interface
     */
    protected $view;

    /**
     * Build a widget, calling options setter
     *
     * @param array $options an array of options
     */
    public function __construct(array $options = array())
    {
        foreach ($options as $option => $value) {
            $method = 'set' . ucfirst($option);
            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }
    }

    /**
     * Partial setter
     *
     * @param string $partial partial for this widget
     *
     * @return Zle_Widget
     */
    public function setPartial($partial)
    {
        $this->partial = (string)$partial;
        return $this;
    }

    /**
     * Return the used partial
     *
     * @return string
     */
    public function getPartial()
    {
        return $this->partial;
    }

    /**
     * View Setter
     *
     * @param Zend_View_Interface $view view used for widget render
     *
     * @return Zle_Widget
     */
    public function setView(Zend_View_Interface $view)
    {
        $this->view = $view;
        return $this;
    }

    /**
     * View Getter
     *
     * @return Zend_View_Interface
     */
    public function getView()
    {
        if (!$this->view) {
            $this->view = new Zend_View();
        }
        return $this->view;
    }

    /**
     * Return the widget content
     *
     * @return string
     */
    public function render()
    {
        return $this->getView()->partial($this->getPartial());
    }
}
