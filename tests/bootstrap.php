<?php

/**
 * Zend Library Extension
 *
 * PHP version 5
 *
 * @category Zle
 * @package  Zle_Test
 * @author   Fabio Napoleoni <f.napoleoni@gmail.com>
 * @license  http://framework.zend.com/license/new-bsd New BSD License
 * @link     http://framework.zend.com/
 */

/*
 * Set error reporting to the level to which Zend Framework code must comply.
 */
error_reporting( E_ALL | E_STRICT );

// add library folder to include_path
set_include_path(
    implode(
        PATH_SEPARATOR,
        array(get_include_path(), realpath(dirname(__FILE__) . '/../library/'))
    )
);

// raise memory limit
ini_set('memory_limit', '512M');

// load the auto loader and register namespaces
require_once 'Zend/Loader/Autoloader.php';
$autoloader = Zend_Loader_Autoloader::getInstance();
$autoloader->registerNamespace('Zle_');
$autoloader->registerNamespace('PHPUnit_');
