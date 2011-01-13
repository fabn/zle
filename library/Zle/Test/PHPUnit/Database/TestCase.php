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

/**
 * Zle_Test_PHPUnit_Database_TestCase
 *
 * @category Zle
 * @package  Zle_Test
 * @author   Fabio Napoleoni <f.napoleoni@gmail.com>
 * @license  http://framework.zend.com/license/new-bsd New BSD License
 * @link     http://framework.zend.com/
 */
abstract class Zle_Test_PHPUnit_Database_TestCase
    extends PHPUnit_Extensions_Database_TestCase
{

    /**
     * @var string
     */
    protected $schemaName = 'zfUnitTests';

    /**
     * Fixture files to load into the dataset
     * @var array
     */
    protected $_fixtures = array();

    /**
     * Return a composite dataset with all the fixtures files loaded
     *
     * @see PHPUnit_Extensions_Database_TestCase::getDataSet()
     *
     * @return  PHPUnit_Extensions_Database_DataSet_IDataSet
     */
    protected function getDataSet()
    {
        $dataSet = new PHPUnit_Extensions_Database_DataSet_CompositeDataSet(
            array()
        );
        // fill dataset with provided fixture files
        foreach ($this->_fixtures as $filename) {
            $dataSet->addDataSet(
                $this->createXMLDataSet(
                    $this->getFixtureFile($filename)
                )
            );
        }
        return $dataSet;
    }

    /**
     * Return the full path to a fixture xml dataset
     *
     * @param string $filename the file to load (relative to the fixtures path)
     *
     * @return string
     */
    protected function getFixtureFile($filename)
    {
        return $this->getFixturesPath() . DIRECTORY_SEPARATOR . $filename;
    }

    /**
     * Return the fixtures path
     *
     * @return string basepath for fixtures
     */
    protected function getFixturesPath()
    {
        if (!defined('APPLICATION_PATH')) {
            throw new Zle_Exception(
                'You must define APPLICATION_PATH constant or override this method'
            );
        }
        return realpath(APPLICATION_PATH . '/../tests/fixtures');
    }
}
