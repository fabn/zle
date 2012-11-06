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

require_once 'Zend/Application.php';
/**
 * Application
 *
 * @category Zle
 * @package  Zle_Application
 * @author   Fabio Napoleoni <f.napoleoni@gmail.com>
 * @license  http://framework.zend.com/license/new-bsd New BSD License
 * @link     http://framework.zend.com/
 */
class Zle_Application extends Zend_Application
{
    const TIMESTAMP_KEY = 'timestamp_of_config';
    const CONFIG_ARRAY_KEY = 'config_array';

    /**
     * @var Zend_Cache_Core
     */
    private $_configCache;

    /**
     * Load configuration file of options
     *
     * @param string $file configuration file
     *
     * @throws Zend_Application_Exception When invalid configuration file is provided
     *
     * @return array
     */
    protected function _loadConfig($file)
    {
        if ($this->isFileUpToDate($file) && $config = $this->getConfigFromCache()) {
            return $config;
        }
        return $this->storeConfigInCache(parent::_loadConfig($file));
    }

    /**
     * Check if the file is changed on the disk
     *
     * @param string $file file to check
     *
     * @return bool
     */
    protected function isFileUpToDate($file)
    {
        if (!file_exists($file)) {
            return false;
        }
        $lastUpdate = filemtime($file);
        $cache = $this->getConfigCache();
        // data not in cache
        if (!($lastVersionTime = $cache->load(self::TIMESTAMP_KEY))) {
            $cache->save($lastUpdate, self::TIMESTAMP_KEY);
            return false;
        }
        if ($lastUpdate > $lastVersionTime) {
            $cache->save($lastUpdate, self::TIMESTAMP_KEY);
            // reload config
            return false;
        }
        // use cache
        return true;
    }

    /**
     * Return configuration from the cache
     *
     * @return array
     */
    protected function getConfigFromCache()
    {
        $cache = $this->getConfigCache();
        if (($config = $cache->load(self::CONFIG_ARRAY_KEY))) {
            return $config;
        }
        return false;
    }

    /**
     * Save configuration in cache and return it
     *
     * @param array $config configuration to save
     *
     * @return array
     */
    protected function storeConfigInCache(array $config)
    {
        $cache = $this->getConfigCache();
        $cache->save($config, self::CONFIG_ARRAY_KEY);
        return $config;
    }

    /**
     * Return a Zend_Cache object with data
     *
     * @return Zend_Cache_Core
     */
    protected function getConfigCache()
    {
        if (!$this->_configCache) {
            $this->_configCache = Zend_Cache::factory(
                'Core', 'Apc',
                array('automatic_serialization' => true)
            );
        }
        return $this->_configCache;
    }
}
