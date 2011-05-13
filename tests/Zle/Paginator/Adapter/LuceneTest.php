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
     * Return an index mock object
     *
     * @return Zend_Search_Lucene_Interface
     */
    protected function getIndex()
    {
        $index = $this->getMock('Zend_Search_Lucene', array(), array(), '', false);
        return $index;
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
}
