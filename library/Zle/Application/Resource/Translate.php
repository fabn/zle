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
 * Translate
 *
 * @category Zle
 * @package  Zle_Application
 * @author   Fabio Napoleoni <f.napoleoni@gmail.com>
 * @license  http://framework.zend.com/license/new-bsd New BSD License
 * @link     http://framework.zend.com/
 */
class Zle_Application_Resource_Translate extends Zend_Application_Resource_Translate
{

    /**
     * Default key for cache manager
     */
    const DEFAULT_CACHE_KEY = 'translator';

    /**
     * Build a log object used internally by parent class
     *
     * @return void
     */
    protected function buildLog()
    {
        if (isset($this->_options['log'])) {
            if (is_array($this->_options['log'])) {
                $this->_options['log'] = Zend_Log::factory($this->_options['log']);
            } else {
                unset($this->_options['log']);
            }
        }
    }

    /**
     * Return string used for cache manager
     *
     * @return string the key used for cache manager
     */
    protected function getCacheKey()
    {
        return isset($this->_options['cacheKey'])
                ? $this->_options['cacheKey']
                : self::DEFAULT_CACHE_KEY;
    }

    /**
     * Retrieve translate object
     *
     * @throws Zend_Application_Resource_Exception if registry key was used
     *          already but is no instance of Zend_Translate
     * @return Zend_Translate
     */
    public function getTranslate()
    {
        if (null === $this->_translate) {
            $this->buildLog();
            // fetch translate object
            $t = parent::getTranslate();
            // retrieve cache if requested
            if (isset($this->_options['cacheEnabled'])
                && $this->_options['cacheEnabled']
            ) {
                // check for cachemanager in bootstrap
                if (!$this->getBootstrap()->hasPluginResource('cachemanager')) {
                    throw new Zend_Application_Resource_Exception(
                        "You must configure the cachemanager with "
                        . "the key {$this->getCacheKey()}"
                    );
                }
                // bootstrap the cachemanager and retrieve it
                /** @var $cacheManager Zend_Cache_Manager */
                $cacheManager = $this->getBootstrap()
                    ->bootstrap('cachemanager')
                    ->getResource('cachemanager');
                // check for the given key
                if (!$cacheManager->hasCache($this->getCacheKey())) {
                    throw new Zend_Application_Resource_Exception(
                        "You must configure the cachemanager with "
                        . "the key {$this->getCacheKey()}"
                    );
                }
                $t->setCache($cacheManager->getCache($this->getCacheKey()));
            }
        }
        return $this->_translate;
    }
}
