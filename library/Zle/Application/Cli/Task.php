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
 * Zle_Application_Cli_Task
 *
 * @category Zle
 * @package  Zle_Application
 * @author   Fabio Napoleoni <f.napoleoni@gmail.com>
 * @license  http://framework.zend.com/license/new-bsd New BSD License
 * @link     http://framework.zend.com/
 */
interface Zle_Application_Cli_Task
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
     * Return the activation flag i.e. the command line flag which
     * enable the task execution
     *
     * @return string if given the task is executed only if
     * the flag is present in the command line
     */
    public function getActivationFlag();

    /**
     * Return the description of this task
     * 
     * @return string
     */
    public function getDescription();
}
