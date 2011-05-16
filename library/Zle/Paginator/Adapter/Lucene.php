<?php

/**
 * Zend Library Extension
 *
 * PHP version 5
 *
 * @category Zle
 * @package  Zle_Paginator
 * @author   Fabio Napoleoni <f.napoleoni@gmail.com>
 * @license  http://framework.zend.com/license/new-bsd New BSD License
 * @link     http://framework.zend.com/
 */

/**
 * Zle_Paginator_Adapter_Lucene, implements paginator adapter with cache support
 *
 * @category Zle
 * @package  Zle_Paginator
 * @author   Fabio Napoleoni <f.napoleoni@gmail.com>
 * @license  http://framework.zend.com/license/new-bsd New BSD License
 * @link     http://framework.zend.com/
 */
class Zle_Paginator_Adapter_Lucene implements Zend_Paginator_Adapter_Interface
{

    /**
     * @var Zend_Search_Lucene_Search_Query
     */
    protected $query;

    /**
     * @var Zend_Search_Lucene_Interface
     */
    protected $index;

    /**
     * @var array search results
     */
    protected $results = array();

    /**
     * @var Zend_Cache_Core instance cache
     */
    protected $cache;

    /**
     * @var Zend_Cache_Core static cache
     */
    protected static $defaultCache;

    /**
     * @var bool
     */
    protected static $useStrongFingerprint = false;

    /**
     * Build a paginator adapter
     *
     * @param Zend_Search_Lucene_Interface           $index instance of lucene index
     * @param string|Zend_Search_Lucene_Search_Query $query the search query
     */
    public function __construct(Zend_Search_Lucene_Interface $index, $query)
    {
        $this->setIndex($index);
        $this->setQuery($query);
    }

    /**
     * Return results count
     *
     * @return int The custom count as an integer
     */
    public function count()
    {
        return count($this->_getResults());
    }

    /**
     * Returns an collection of items for a page.
     *
     * @param integer $offset           Page offset
     * @param integer $itemCountPerPage Number of items per page
     *
     * @return array
     */
    public function getItems($offset, $itemCountPerPage)
    {
        return array_slice($this->_getResults(), $offset, $itemCountPerPage);
    }

    /**
     * Query setter, strings or query expected
     *
     * @param string|Zend_Search_Lucene_Search_Query $query the search query
     *
     * @throws Zend_Search_Exception if none of them are given
     *
     * @return void
     */
    public function setQuery($query)
    {
        if (is_string($query)) {
            $query = Zend_Search_Lucene_Search_QueryParser::parse($query);
        }
        if (!$query instanceof Zend_Search_Lucene_Search_Query) {
            throw new Zend_Search_Exception(
                sprintf("Expected query or string %s given", get_class($query))
            );
        }
        $this->query = $query;
    }

    /**
     * Query getter
     *
     * @return Zend_Search_Lucene_Search_Query
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * Index setter
     *
     * @param Zend_Search_Lucene_Interface $index A lucene index
     *
     * @return void
     */
    public function setIndex($index)
    {
        $this->index = $index;
    }

    /**
     * Index getter
     *
     * @return Zend_Search_Lucene_Interface
     */
    public function getIndex()
    {
        return $this->index;
    }

    /**
     * Return the executed query
     *
     * @return Zend_Search_Lucene_Search_Query
     */
    protected function getExecutedQuery()
    {
        return $this->getQuery()->rewrite($this->getIndex())->optimize($this->getIndex());
    }

    /**
     * Used to build a unique fingerprint for the executed query
     *
     * @return string
     */
    public function queryFingerPrint()
    {
        $signatures = array();
        foreach ($this->getExecutedQuery()->getQueryTerms() as $term) {
            $signatures[] = md5(serialize($term));
        }
        return md5(serialize(sort($signatures)));
    }

    /**
     * Return a weak fingerprint for the given query
     *
     * @return string
     */
    public function queryWeakFingerprint()
    {
        return md5(serialize($this->getQuery()));
    }

    /**
     * Make the search if not yet executed and return results
     *
     * @return array
     */
    private function _getResults()
    {
        if ($results = $this->_loadResultsFromCache()) {
            // cache hit
            return $results;
        }
        if (empty($this->results)) {
            // build results
            $this->results = $this->getIndex()->find($this->getQuery());
            // store them in cache if requested
            $this->_saveResultsInCache($this->results);
        }
        // get results back
        return $this->results;
    }

    /**
     * Try to fetch results from the cache if configured
     *
     * @return array|null
     */
    private function _loadResultsFromCache()
    {
        if (!$this->getCache()) {
            // no cache configured
            return null;
        }
        // cache is used, build query fingerprint
        if ($results = $this->_actualLoadFromCache()) {
            return $results;
        }
        // cache miss
        return null;
    }

    /**
     * Save executed search in cache
     *
     * @param array $results found results
     *
     * @return void
     */
    private function _saveResultsInCache($results)
    {
        if (!$cache = $this->getCache()) {
            // cache is not used
            return;
        }
        if (self::$useStrongFingerprint) {
            $strongFp = $this->queryFingerPrint();
            // saves two records in cache
            $cache->save($results, $strongFp);
            $cache->save($strongFp, $this->queryWeakFingerprint());
        } else {
            // save only the weak fingerprint
            $cache->save($results, $this->queryWeakFingerprint());
        }
    }

    /**
     * Return results from cache
     *
     * @return array|null
     */
    private function _actualLoadFromCache()
    {
        $cache = $this->getCache();
        if (self::$useStrongFingerprint) {
            // lookup for strong fingerprint based on the weak one
            $cacheId = $cache->load($this->queryWeakFingerprint());
        } else {
            // use weak fingerprint to lookup
            $cacheId = $this->queryWeakFingerprint();
        }
        // try to fetch results using the given id
        return $cacheId
                ? $this->getCache()->load($cacheId)
                : null;
    }

    /**
     * Set cache to be used for this instance
     *
     * @param Zend_Cache_Core $cache a cache object
     *
     * @return void
     */
    public function setCache(Zend_Cache_Core $cache)
    {
        $this->cache = $cache;
    }

    /**
     * Return the cache object
     *
     * @return Zend_Cache_Core
     */
    protected function getCache()
    {
        if ($this->cache) {
            // instance cache
            return $this->cache;
        } else if (self::$defaultCache) {
            return self::getDefaultCache();
        } else {
            return null;
        }
    }

    /**
     * Set cache for all instances
     *
     * @param Zend_Cache_Core $cache the cache object
     *
     * @return void
     */
    public static function setDefaultCache(Zend_Cache_Core $cache = null)
    {
        self::$defaultCache = $cache;
    }

    /**
     * Return global cache object
     *
     * @return Zend_Cache_Core
     */
    public static function getDefaultCache()
    {
        return self::$defaultCache;
    }

    /**
     * Configure the class to use only weakFingerprint algorithm
     *
     * @param bool $flag boolean flag
     *
     * @return void
     */
    public static function setUseWeakFingerPrintOnly($flag = true)
    {
        self::$useStrongFingerprint = !$flag;
    }
}
