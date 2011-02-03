<?php

/**
 * Zend Library Extension
 *
 * PHP version 5
 *
 * @category Zle
 * @package  Zle_Mail
 * @author   Fabio Napoleoni <f.napoleoni@gmail.com>
 * @license  http://framework.zend.com/license/new-bsd New BSD License
 * @link     http://framework.zend.com/
 */

/**
 * Zle_Mail_Mvc
 *
 * @category Zle
 * @package  Zle_Mail
 * @author   Fabio Napoleoni <f.napoleoni@gmail.com>
 * @license  http://framework.zend.com/license/new-bsd New BSD License
 * @link     http://framework.zend.com/
 */
class Zle_Mail_Mvc extends Zend_Mail
{
    /**
     * @var Zend_Layout
     */
    private $_layout;

    /**
     * @var Zend_View
     */
    public $view;

    /**
     * @var string
     */
    private $_htmlLayout;

    /**
     * @var string
     */
    private $_txtLayout;

    /**
     * @var string
     */
    private $_htmlView;

    /**
     * @var string
     */
    private $_txtView;

    /**
     * @var string
     */
    private $_applicationPath;

    /**
     * Public constructor
     *
     * @param array  $viewOptions   options given to the view constructor
     * @param array  $layoutOptions options given to the layout constructor
     * @param string $charset       charset for the message
     */
    public function __construct($viewOptions = array(), $layoutOptions = array(), $charset = 'iso-8859-1')
    {
        $this->view = new Zend_View($viewOptions);
        $this->_layout = new Zend_Layout($layoutOptions);
        parent::__construct($charset);
    }

    /**
     * Magic method, non existent methods will be delegated to the view object
     *
     * @param string $name      The name of the called method
     * @param array  $arguments Arguments for method
     *
     * @throws Zle_Mail_Exception if the method doesn't exist in the view object
     *
     * @return mixed return value of the called method
     */
    public function __call($name, $arguments)
    {
        if (method_exists($this->view, $name)) {
            return call_user_func_array(array($this->view, $name), $arguments);
        } else {
            throw new Zle_Mail_Exception('Unknown method ' . $name);
        }
    }

    /**
     * Set the layout script for the html part
     *
     * @param string $layout the layout script to use
     *
     * @return void
     */
    public function setHtmlLayout($layout)
    {
        $this->_htmlLayout = $layout;
    }

    /**
     * Return the current html layout script
     *
     * @return string
     */
    public function getHtmlLayout()
    {
        return $this->_htmlLayout;
    }

    /**
     * Set the layout script for the txt part
     *
     * @param string $layout the layout script to use
     *
     * @return void
     */
    public function setTxtLayout($layout)
    {
        $this->_txtLayout = $layout;
    }

    /**
     * Return the current txt layout script
     *
     * @return string
     */
    public function getTxtLayout()
    {
        return $this->_txtLayout;
    }

    /**
     * Set the view script for the html part
     *
     * @param string $view the view script to use
     *
     * @return void
     */
    public function setHtmlView($view)
    {
        $this->_htmlView = $view;
    }

    /**
     * Return the current html view script
     *
     * @return string
     */
    public function getHtmlView()
    {
        return $this->_htmlView;
    }

    /**
     * Set the view script for the txt part
     *
     * @param string $view the view script to use
     *
     * @return void
     */
    public function setTxtView($view)
    {
        $this->_txtView = $view;
    }

    /**
     * Return the current txt view script
     *
     * @return string
     */
    public function getTxtView()
    {
        return $this->_txtView;
    }

    /**
     * Sends this email using the given transport or a previously
     * set DefaultTransport or the internal mail function if no
     * default transport had been set.
     *
     * @param Zend_Mail_Transport_Abstract $transport transport to use
     *
     * @return Zend_Mail Provides fluent interface
     */
    public function send($transport = null)
    {
        // build body using the provided layout and view
        return parent::send($transport);
    }

    /**
     * Set the application path with this method, otherwise the constant
     * APPLICATION_PATH will be used.
     *
     * @param string $path the application path
     *
     * @return void
     */
    public function setApplicationPath($path)
    {
        $this->_applicationPath = $path;
    }

    /**
     * Return the application path defined or APPLICATION_PATH by default.
     *
     * @throws Zle_Mail_Exception if path is not set and APPLICATION_PATH is
     *         not defined
     *
     * @return string
     */
    public function getApplicationPath()
    {
        if ($this->_applicationPath) {
            return $this->_applicationPath;
        } else if (defined('APPLICATION_PATH')) {
            return APPLICATION_PATH;
        } else {
            throw new Zle_Mail_Exception(
                'You must set or define the application path'
            );
        }
    }
}
