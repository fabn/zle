<?php

/**
 * Zend Library Extension
 *
 * PHP version 5
 *
 * @category Zle
 * @package  Zle_Application
 * @author   Fabio Napoleoni <f.napoleoni@gmail.com>
 * @license  http://framework.zend.com/license/new-bsd New BSD License
 * @link     http://framework.zend.com/
 */

/**
 * Zle_Application_CliAbstract
 *
 * @category Zle
 * @package  Zle_Application
 * @author   Fabio Napoleoni <f.napoleoni@gmail.com>
 * @license  http://framework.zend.com/license/new-bsd New BSD License
 * @link     http://framework.zend.com/
 */
abstract class Zle_Application_Cli_CliAbstract implements Zle_Application_Cli_Cli
{
    /**
     * @var Zend_Application
     */
    private $_application;

    /**
     * @var Zend_Console_Getopt
     */
    private $_options;

    /**
     * @var bool value for default options
     */
    protected $hasDefaultOptions = true;

    /**
     * @var array default options
     */
    protected $defaultOptions = array(
        'environment|e=w' => 'Environment to use as specified in application.ini (default production)',
        'help|h' => 'Show program usage');

    /**
     * Must implement an empty constructor
     */
    public function __construct()
    {

    }

    /**
     * Run this cli application after initialization
     *
     * @return void
     */
    public function run()
    {
        throw new Zend_Application_Exception(
            'Override this method to provide functionality to your cli application'
        );
    }

    /**
     * Return an array of options compatible with Zend_Console_Getopt
     *
     * @return array
     */
    public final function getOptions()
    {
        // !!! should not rely on autoloading
        if (!$this->hasDefaultOptions()) {
            return $this->getActualOptions();
        }
        return array_merge($this->getActualOptions(), $this->defaultOptions);
    }

    /**
     * Override in subclasses to provide specific options to the application,
     * <strong>this method should not rely on class autoloading</strong>
     *
     * @return array
     */
    protected function getActualOptions()
    {
        // !!! should not rely on autoloading
        return array();
    }

    /**
     * Options setter
     *
     * @param Zend_Console_Getopt $options options for the application
     *
     * @return Zle_Application_Cli_Cli fluent interface
     */
    public function setOptions(Zend_Console_Getopt $options)
    {
        $this->_options = $options;
        // TODO implements checks on options based on missing methods
        return $this;
    }

    /**
     * Zend_Application setter
     *
     * @param Zend_Application $app the application object
     *
     * @return Zle_Application_Cli_Cli fluent interface
     */
    public function setApplication(Zend_Application $app)
    {
        $this->_application = $app;
        return $this;
    }

    /**
     * Zend_Application getter
     *
     * @return Zend_Application
     */
    public function getApplication()
    {
        return $this->_application;
    }

    /**
     * Return true if the application supports -e and -h switches, used for
     * setting the environment and for showing the help message
     *
     * @return bool
     */
    public function hasDefaultOptions()
    {
        return $this->hasDefaultOptions;
    }

    // TODO implement these methods and test it
//    protected function getMandatoryOptions() {
//        return array('u', 'v');
//    }
//
//    protected function getIncompatibleOptions() {
//        return array('a' => 'b', 'b' => array('c', 'd'), 'c' => self::ONLY);
//    }
//
//    protected function getDependentOptions() {
//        return array('e' => 'a', 'f' => 'b');
//    }
}
