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
 * Test for Lucene paginator Adapter
 *
 * @category Zle
 * @package  Zle_Test
 * @author   Fabio Napoleoni <f.napoleoni@gmail.com>
 * @license  http://framework.zend.com/license/new-bsd New BSD License
 * @link     http://framework.zend.com/
 */
class LuceneTest extends PHPUnit_Framework_TestCase
{

    /**
     * Return index directory for the real index
     *
     * @return string
     */
    protected static function indexDir()
    {
        return sprintf("%s/_index", __DIR__);
    }

    /**
     * This method is called before the first test of this test class is run.
     *
     * Build a temporary index with a couple of documents
     */
    public static function setUpBeforeClass()
    {
        // ensure no index exist
        system(sprintf("rm -rf %s", self::indexDir()));
        // create the index
        $index = Zend_Search_Lucene::create(self::indexDir());
        // add some documents
        for ($i = 0; $i < 3; $i++) {
            $document = new Zend_Search_Lucene_Document();
            $document->addField(Zend_Search_Lucene_Field::text('title', "foo bar baz $i"));
            $document->addField(Zend_Search_Lucene_Field::text('content', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc pharetra laoreet sodales. Aenean consequat ornare aliquam. Etiam vestibulum ultrices elit nec vestibulum'));
            $index->addDocument($document);
        }
        $index->commit();
    }

    /**
     * This method is called after the last test of this test class is run.
     *
     * Cleanup index built in setUpBeforeClass
     */
    public static function tearDownAfterClass()
    {
        // cleanup index directory
        system(sprintf("rm -rf %s", self::indexDir()));
    }

    /**
     * Return a real index object built during setup
     *
     * @return Zend_Search_Lucene_Interface
     */
    protected function getRealIndex()
    {
        return Zend_Search_Lucene::open(self::indexDir());
    }

    /**
     * Return an index mock object
     *
     * @return Zend_Search_Lucene_Interface
     */
    protected function getIndex()
    {
        $index = $this->getMock('Zend_Search_Lucene', array(), array(), '', false);
        return $index;
    }

    /**
     * Return a cache object cleared on tearDown
     *
     * @return Zend_Cache_Core|Zend_Cache_Frontend
     */
    protected function getCache()
    {
        return Zend_Cache::factory('Core', 'Apc', array('automatic_serialization' => true));
    }

    protected function tearDown()
    {
        $this->getCache()->clean();
        Zle_Paginator_Adapter_Lucene::setDefaultCache(null);
        Zle_Paginator_Adapter_Lucene::setUseWeakFingerPrintOnly(true);
    }

    public function testQueryCanBeStringOrQueryObject()
    {
        $q = Zend_Search_Lucene_Search_QueryParser::parse('foo');
        $a = new Zle_Paginator_Adapter_Lucene($this->getIndex(), 'foo');
        $this->assertEquals($q, $a->getQuery());
        $a = new Zle_Paginator_Adapter_Lucene($this->getIndex(), $q);
        $this->assertEquals($q, $a->getQuery());
        try {
            new Zle_Paginator_Adapter_Lucene($this->getIndex(), new stdClass());
        } catch (Zend_Search_Exception $e) {
            $this->assertEquals(
                "Expected query or string stdClass given",
                $e->getMessage()
            );
        }
    }

    public function testCountReturnsResultsCount()
    {
        $index = $this->getIndex();
        $result = array(1, 2);
        $index->expects($this->any())->method('find')->will($this->returnValue($result));
        $a = new Zle_Paginator_Adapter_Lucene($index, 'foo');
        $this->assertEquals(count($result), $a->count());
    }

    public function testGetItemsReturnsCorrectSliceOfArray()
    {
        $index = $this->getIndex();
        $result = array_fill(0, 10, 'page1') + array_fill(10, 10, 'page2');
        $index->expects($this->any())->method('find')->will($this->returnValue($result));
        $a = new Zle_Paginator_Adapter_Lucene($index, 'foo');
        $this->assertEquals(array_fill(0, 10, 'page2'), $a->getItems(10, 10));
    }

    public function testPaginatorUsesCacheIfSetForInstance()
    {
        $index = $this->getIndex();
        $q = Zend_Search_Lucene_Search_QueryParser::parse('foo');
        $result = array_fill(0, 10, 'page1') + array_fill(10, 10, 'page2');
        $index->expects($this->once())->method('find')->will($this->returnValue($result));
        $a1 = new Zle_Paginator_Adapter_Lucene($index, $q);
        $a1->setCache($this->getCache());
        $a2 = new Zle_Paginator_Adapter_Lucene($index, $q);
        $a2->setCache($this->getCache());
        // do searches with different instances of adapter
        $this->assertEquals($a1->getItems(0, 10), $a2->getItems(0, 10));
        $this->assertEquals(array_fill(0, 10, 'page2'), $a2->getItems(10, 10));
    }

    public function testPaginatorUsesCacheIfStaticallySet()
    {
        $index = $this->getIndex();
        $q = Zend_Search_Lucene_Search_QueryParser::parse('foo');
        $result = array_fill(0, 10, 'page1') + array_fill(10, 10, 'page2');
        $index->expects($this->once())->method('find')->will($this->returnValue($result));
        Zle_Paginator_Adapter_Lucene::setDefaultCache($this->getCache());
        $a1 = new Zle_Paginator_Adapter_Lucene($index, $q);
        $a2 = new Zle_Paginator_Adapter_Lucene($index, $q);
        // do searches with different instances of adapter
        $this->assertEquals($a1->getItems(0, 10), $a2->getItems(0, 10));
        $this->assertEquals(array_fill(0, 10, 'page2'), $a2->getItems(10, 10));
    }

    public function testPaginatorWithWeakFingerPrintAndDifferentQueries()
    {
        $index = $this->getIndex();
        $q1 = Zend_Search_Lucene_Search_QueryParser::parse('foo bar');
        $q2 = Zend_Search_Lucene_Search_QueryParser::parse('bar foo');
        $result = array_fill(0, 10, 'page1') + array_fill(10, 10, 'page2');
        $index->expects($this->exactly(2))->method('find')->will($this->returnValue($result));
        Zle_Paginator_Adapter_Lucene::setDefaultCache($this->getCache());
        $a1 = new Zle_Paginator_Adapter_Lucene($index, $q1);
        $a2 = new Zle_Paginator_Adapter_Lucene($index, $q2);
        // do searches with different instances of adapter
        $this->assertEquals($a1->getItems(0, 10), $a2->getItems(0, 10));
    }

    public function testPaginatorWithStrongFingerPrintAndDifferentQueries()
    {
        $index = $this->getRealIndex();
        $q1 = Zend_Search_Lucene_Search_QueryParser::parse('foo bar');
        $q2 = Zend_Search_Lucene_Search_QueryParser::parse('bar foo');
        Zle_Paginator_Adapter_Lucene::setDefaultCache($this->getCache());
        Zle_Paginator_Adapter_Lucene::setUseWeakFingerPrintOnly(false);
        $a1 = new Zle_Paginator_Adapter_Lucene($index, $q1);
        $a2 = new Zle_Paginator_Adapter_Lucene($index, $q2);
        // do searches with different instances of adapter
        $this->assertEquals($a1->getItems(0, 10), $a2->getItems(0, 10));
    }

    public function testQuerySimilarity()
    {
        $q1 = Zend_Search_Lucene_Search_QueryParser::parse('foo bar baz');
        $q2 = Zend_Search_Lucene_Search_QueryParser::parse('bar baz foo');
        $realIndex = $this->getRealIndex();
        $a1 = new Zle_Paginator_Adapter_Lucene($realIndex, $q1);
        $a2 = new Zle_Paginator_Adapter_Lucene($realIndex, $q2);
        $this->assertEquals(
            $a1->queryFingerPrint(), $a2->queryFingerPrint(),
            "Query fingerprints should be the same with inverted words"
        );
    }
}
