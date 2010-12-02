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
 * TaskAbstract
 *
 * @category Zle
 * @package  Zle_Application
 * @author   Fabio Napoleoni <f.napoleoni@gmail.com>
 * @license  http://framework.zend.com/license/new-bsd New BSD License
 * @link     http://framework.zend.com/
 */
class Zle_Cli_TaskAbstract implements Zle_Application_Cli_Task
{

    /**
     * Must implement an empty constructor
     */
    public function __construct()
    {
        // TODO: Implement __construct() method.
    }

    /**
     * Return the activation flag i.e. the command line flag which
     * enable the task execution
     *
     * @return string if given the task is executed only if
     * the flag is present in the command line
     */
    public function getActivationFlag()
    {
        // TODO: Implement getActivationFlag() method.
    }

    /**
     * Return the description of this task
     *
     * @return string
     */
    public function getDescription()
    {
        // TODO: Implement getDescription() method.
    }

    /**
     * Run this cli application after initialization
     *
     * @return void
     */
    public function run()
    {
        // TODO: Implement run() method.
    }
}
