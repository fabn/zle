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
 * Zle_Application_Runner
 *
 * @category Zle
 * @package  Zle_Application
 * @author   Fabio Napoleoni <f.napoleoni@gmail.com>
 * @license  http://framework.zend.com/license/new-bsd New BSD License
 * @link     http://framework.zend.com/
 */
class Zle_Application_Runner
{
    /**
     * Run a {@link Zle_Application_Cli_Cli} application
     *
     * @param Zle_Application_Cli_Cli|string $application an instance
     * application or a name of a class which implements it
     *
     * @return void
     */
    public static function run($application)
    {
        $application = self::_instantiate($application, 'Zle_Application_Cli_Cli');
        /** @var $application Zle_Application_Cli_Cli */
        $options = self::parseOptions($application->getOptions());
        self::initApplication($application, $options);
        // after this line auto loading will be available
        self::_runApp($application);
    }

    /**
     * Run a collection of {@link Zle_Application_Cli_Task}
     *
     * @param array $tasks an array of {@link Zle_Application_Cli_Task}
     * elements provided either as instances or as names of classes which implement
     * that interface
     *
     * @return void
     */
    public static function runTasks(array $tasks = array())
    {
        $application = new Zle_Application_Cli_TaskRunner();
        foreach ($tasks as $task) {
            $application->registerTask(
                self::_instantiate($task, 'Zle_Application_Cli_Task')
            );
        }
        $options = self::parseOptions($application->getOptions());
        self::initApplication($application, $options);
        // after this line auto loading will be available
        self::_runApp($application);
    }

    /**
     * Instantiate an object of the given class and test for its
     * "instanceofness" for the given class, otherwise, if it is given
     * as an object it will be only tested for "instanceofness"
     *
     * @param mixed  $object the object
     * @param string $class  the class to test for
     *
     * @throws Zend_Application_Exception if object doesn't meet criteria
     * @return mixed an object of class $class
     */
    private static function _instantiate($object, $class)
    {
        if (is_string($object)) {
            if (!class_exists($object)) {
                require_once 'Zend/Application/Exception.php';
                throw new Zend_Application_Exception(
                    "Class $object does not exist"
                );
            }
            // check if it is instantiable with no arguments
            $r = new ReflectionClass($object);
            if (count($r->getConstructor()->getParameters()) > 0) {
                require_once 'Zend/Application/Exception.php';
                throw new Zend_Application_Exception(
                    "Class $object does not have an empty constructor"
                );
            }
            $application = new $object();
        }
        if (!$application instanceof $class) {
            require_once 'Zend/Application/Exception.php';
            throw new Zend_Application_Exception(
                "\$application should be an instance of $class"
            );
        }
        return $application;
    }

    /**
     * Run the application, catch the exceptions and log them if a logger
     * is available in Zend_Application attacched to the app
     *
     * @param Zle_Application_Cli_Cli $application application instance
     *
     * @return void
     */
    private static function _runApp(Zle_Application_Cli_Cli $application)
    {
        /** @var Zend_Application $app */
        $app = $application->getApplication();
        if ($app->getBootstrap()->hasPluginResource('log')) {
            $log = $app->getBootstrap()->getResource('log');
        } else {
            $log = new Zend_Log(new Zend_Log_Writer_Stream('php://stderr'));
        }
        try { // to run the app
            $application->run();
        } catch (Exception $e) {
            $log->crit('Error during execution: ' . $e->getMessage(), $e);
        }
    }

    /**
     * Build a Zend_Console_Getopt object, parse options and returns them, if
     * an error was raised it catch it and exit
     *
     * @param array $options options for the application
     *
     * @return Zend_Console_Getopt
     */
    protected static function parseOptions(array $options)
    {
        require_once 'Zend/Console/Getopt.php';
        if (defined('APPLICATION_ENV') && APPLICATION_ENV == 'testing') {
            // this sucks, but better than nothing!!!
            return new Zend_Console_Getopt($options);
        }
        // using Zend_Console_Getopt to parse environment to load
        try {
            $getOpt = new Zend_Console_Getopt($options);
            $getOpt->parse();
            return $getOpt;
        } catch (Zend_Console_Getopt_Exception $e) {
            echo $e->getMessage() . PHP_EOL;
            echo $e->getUsageMessage();
            exit(1);
        }
    }

    /**
     * Initialize Zend_Application object
     *
     * @param Zle_Application_Cli_Cli $application application instance
     * @param Zend_Console_Getopt     $options     options for app
     *
     * @return void
     */
    protected static function initApplication($application, $options)
    {
        // this whole method sucks!!!
        if (!defined('APPLICATION_PATH')) {
            throw new Exception('You must define APPLICATION_PATH to use this class');
        }
        if (defined('APPLICATION_ENV')) {
            throw new Exception('You must not define APPLICATION_ENV to use this class');
        }
        // build library path
        $libraryPath = realpath(APPLICATION_PATH . '/../library');
        // Ensure APP_ROOT/library/ is in include_path
        set_include_path(
            implode(PATH_SEPARATOR, array($libraryPath, get_include_path()))
        );
        // find environment to load
        if ($application->hasDefaultOptions()) {
            $environment = $options->getOption('e')
                    ? $options->getOption('e') : 'production';
        } else {
            $environment = getenv('APPLICATION_ENV')
                    ? getenv('APPLICATION_ENV') : 'production';
        }
        // define application environment
        define('APPLICATION_ENV', $environment);
        /** Zend_Application */
        require_once 'Zend/Application.php';
        // Init a Zend_Application
        $zendApplication = new Zend_Application(
            APPLICATION_ENV,
            realpath(APPLICATION_PATH . '/configs/application.ini')
        );
        $application->setApplication($zendApplication);
        try {
            $application->setOptions($options);
        } catch (Exception $e) {
            echo $e->getMessage() . PHP_EOL;
            echo $options->getUsageMessage();
            exit(1);
        }
    }
}
