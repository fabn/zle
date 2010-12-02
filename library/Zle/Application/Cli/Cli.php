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
 * Zle_Application_Cli_Cli
 *
 * @category Zle
 * @package  Zle_Application
 * @author   Fabio Napoleoni <f.napoleoni@gmail.com>
 * @license  http://framework.zend.com/license/new-bsd New BSD License
 * @link     http://framework.zend.com/
 */
interface Zle_Application_Cli_Cli
{
    /**
     * Must implement an empty constructor
     */
    public function __construct();

    /**
     * Run this cli application after initialization
     *
     * @return void
     */
    public function run();

    /**
     * Return an array of options compatible with Zend_Console_Getopt
     *
     * @return array
     */
    public function getOptions();

    /**
     * Options setter
     *
     * @param Zend_Console_Getopt $options options for the application
     *
     * @return Zle_Application_Cli_Cli fluent interface
     */
    public function setOptions(Zend_Console_Getopt $options);

    /**
     * Zend_Application setter
     *
     * @param Zend_Application $app the application object
     *
     * @return Zle_Application_Cli_Cli fluent interface
     */
    public function setApplication(Zend_Application $app);

    /**
     * Zend_Application getter
     *
     * @return Zend_Application
     */
    public function getApplication();

    /**
     * Return true if the application supports -e and -h switches, used for
     * setting the environment and for showing the help message
     *
     * @return bool
     */
    public function hasDefaultOptions();
}
