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
            $query = Zend_Search_Lucene_Search_QueryParser::parse('foo');
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
     * Make the search if not yet executed and return results
     *
     * @return array
     */
    private function _getResults()
    {
        if (empty($this->results)) {
            $this->results = $this->getIndex()->find($this->getQuery());
        }
        return $this->results;
    }
}
