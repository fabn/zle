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
 * Zle_Application_Cli_TaskRunner
 *
 * @category Zle
 * @package  Zle_Application
 * @author   Fabio Napoleoni <f.napoleoni@gmail.com>
 * @license  http://framework.zend.com/license/new-bsd New BSD License
 * @link     http://framework.zend.com/
 */
class Zle_Application_Cli_TaskRunner extends Zle_Application_Cli_CliAbstract
{
    /**
     * @var array list of tasks
     */
    private $_tasks = array();

    /**
     * Register a task for the execution
     *
     * @param Zle_Application_Cli_Task $task a task to be registered
     *
     * @return void
     */
    public function registerTask(Zle_Application_Cli_Task $task)
    {
        // TODO implement the registerTask method
    }

    /**
     * Return a list of flags based on tasks list
     *
     * @return array
     */
    protected function getActualOptions()
    {
        // TODO add options based on registered tasks
        return array();
    }
}
