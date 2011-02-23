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
     * @var bool
     */
    private $_isBodyBuilt = false;

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
        $this->_htmlLayout = $this->getScriptName($layout, false);
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
        $this->_txtLayout = $this->getScriptName($layout, false);
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
        $this->_htmlView = $this->getScriptName($view, true);
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
        $this->_txtView = $this->getScriptName($view, true);
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
     * Return the name of the script handling differences between
     * layouts and views
     *
     * @param string $name      name of the script
     * @param bool   $viewStyle if true .phtml suffix will be enforced, otherwise
     *                          it will be stripped from the name
     *
     * @return string
     */
    protected function getScriptName($name, $viewStyle)
    {
        $hasSuffix = 'phtml' == substr($name, -5);
        if ($viewStyle && !$hasSuffix) {
            $name .= '.phtml';
        }
        if (!$viewStyle && $hasSuffix) {
            $name = substr($name, 0, -6);
        }
        return $name;
    }

    /**
     * Build body of message
     *
     * @param bool $force if true the body will be overwritten
     *      if it's already built. Don't make any change if $force is false
     *      and the body is already generated.
     *
     * @return void
     */
    public function buildMessage($force = false)
    {
        if ($force || !$this->_isBodyBuilt) {
            // prepare the view
            $this->_prepareView();
            // prepare the layout
            $this->_prepareLayout();
            // build the html body
            $this->_buildHtmlBody();
            // build the txt body
            $this->_buildTextBody();
            // set flag to built
            $this->_isBodyBuilt = true;
        }
    }

    /**
     * Build the html part of the email using the provided scripts
     *
     * @return void
     */
    private function _buildHtmlBody()
    {
        $viewContent = '';
        if ($this->getHtmlView()) {
            $viewContent = $this->view->render($this->getHtmlView());
            if (!$this->getHtmlLayout()) {
                // no layout, render only the view
                $this->setBodyHtml($viewContent);
            }
        }
        if ($this->getHtmlLayout()) {
            $this->_layout->setLayout($this->getHtmlLayout());
            $this->_layout->setView($this->view);
            // assign rendered view to the layout
            $this->_layout->assign('content', $viewContent);
            // render the layout
            $this->setBodyHtml($this->_layout->render($this->getHtmlLayout()));
        }
    }

    /**
     * Build the txt part of the email using the provided scripts
     *
     * @return void
     */
    private function _buildTextBody()
    {
        $viewContent = '';
        if ($this->getTxtView()) {
            $viewContent = $this->view->render($this->getTxtView());
            if (!$this->getTxtLayout()) {
                // no layout, render only the view
                $this->setBodyText($viewContent);
            }
        }
        if ($this->getTxtLayout()) {
            $this->_layout->setLayout($this->getTxtLayout());
            $this->_layout->setView($this->view);
            // assign rendered view to the layout
            $this->_layout->assign('content', $viewContent);
            // render the layout
            $this->setBodyText($this->_layout->render($this->getTxtLayout()));
        }
    }

    /**
     * Prepare layout instance to be used
     *
     * @return void
     */
    private function _prepareLayout()
    {
        // set the view object
        $this->_layout->setView($this->view);
        // set the layout path
        $this->_layout->setLayoutPath(
            $this->getApplicationPath() . '/layouts/scripts/'
        );
    }

    /**
     * Prepare view to be used
     *
     * @return void
     */
    private function _prepareView()
    {
        $defaultScriptPath = $this->getApplicationPath() . '/views/scripts/';
        if (!in_array($defaultScriptPath, $this->view->getScriptPaths())) {
            $this->view->addScriptPath($defaultScriptPath);
        }
        $defaultHelperPath = $this->getApplicationPath() . '/views/helpers/';
        if (!in_array($defaultHelperPath, $this->view->getHelperPaths())) {
            $this->view->addHelperPath($defaultHelperPath);
        }
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
        $this->buildMessage();
        // call parent method to send
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
