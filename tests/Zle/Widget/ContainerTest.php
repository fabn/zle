<?php

class ContainerTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Zle_Widget
     */
    protected $widget;

    /**
     * @var Zle_Widget_Container
     */
    protected $container;

    protected function setUp()
    {
        $this->container = new Zle_Widget_Container();
    }

    protected function tearDown()
    {
        Zle_Widget_Container::resetAllAreas();
    }


    /**
     * Widget factory
     *
     * @param array $options widget options that replaces default
     *
     * @return Zle_Widget
     */
    protected function getWidget(array $options = array())
    {
        $defaultOptions = array(
            'title' => sprintf("Widget #%d", $this->counter()),
            'name' => 'foo', 'model' => array(),
            'view' => $this->getView(), 'partial' => 'foo.phtml',
        );
        $options = array_merge($defaultOptions, $options);
        return new Zle_Widget($options);
    }

    /**
     * Return a configured view object
     *
     * @return Zend_View
     */
    protected function getView()
    {
        $view = new Zend_View();
        return $view->setScriptPath(__DIR__ . '/_files');
    }

    /**
     * Acts like a counter, starting from 0
     *
     * @return int
     */
    protected function counter()
    {
        return $this->counter++;
    }

    public function testForEachShouldRespectTheGivenOrderWhenInserted()
    {
        for ($i = 3; $i >= 0; $i--) {
            $this->container->insert(
                $this->getWidget(array('order' => $i))
            );
        }
        foreach ($this->container as $widget) {
            /* @var $widget Zle_Widget */
            $returned_order[] = $widget->getOrder();
        }
        // make a sorted array
        $sorted_order = $returned_order;
        sort($sorted_order);
        $this->assertEquals(
            $sorted_order, $returned_order,
            "Widgets should be returned in order when inserted"
        );
    }

    /**
     * Test for shouldReturnDifferentInstances
     */
    public function testGetAreaShouldReturnDifferentInstances()
    {
        $this->assertNotSame(
            Zle_Widget_Container::getArea(),
            Zle_Widget_Container::getArea('leftSideBar'),
            "Different areas should be returned"
        );
    }

    /**
     * Test for unsetAreaShouldClearArea
     */
    public function testUnsetAreaShouldClearArea()
    {
        $area = Zle_Widget_Container::getArea();
        $area->append($this->getWidget());
        $this->assertEquals(1, count(Zle_Widget_Container::getArea()));
        Zle_Widget_Container::resetArea();
        $this->assertEquals(0, count(Zle_Widget_Container::getArea()));
    }

    /**
     * Test for shouldHaveARenderMethod
     */
    public function testShouldHaveARenderMethod()
    {
        $this->container->append($this->getWidget(array('title' => 'Title 1')));
        $this->container->append($this->getWidget(array('title' => 'Title 2')));
        $this->assertRegExp('/Title 1.*Title 2/s', $this->container->render());
    }
}
