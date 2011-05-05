<?php

class WidgetTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var Zle_Widget
     */
    protected $widget;

    protected function setUp()
    {
        $this->widget = new Zle_Widget();
    }

    public function testWidgetClassExists()
    {
        $this->assertInstanceOf('Zle_Widget', $this->widget);
    }

    public function settersAndGettersProvider()
    {
        return array(
            array('partial', 'foo.phtml'),
            array('view', new Zend_View()),
            array('model', 'model'),
        );
    }

    /**
     * @dataProvider settersAndGettersProvider
     * @param string $property
     * @param string $value
     * @return void
     */
    public function testWidgetPropertySetterAndGetter($property, $value)
    {
        $setter = 'set' . ucfirst($property);
        $getter = 'get' . ucfirst($property);
        $this->assertSame(
            $this->widget, $this->widget->$setter($value),
            "Setter for $property should implement fluent interface"
        );
        $this->assertSame($value, $this->widget->$getter());
    }

    public function testViewIsNotMandatoryForWidget()
    {
        $this->assertInstanceOf('Zend_View_Interface', $this->widget->getView());
    }

    public function testRenderMethodRenderThePartialUsingTheView()
    {
        $view = new Zend_View();
        $view->setScriptPath(__DIR__ . '/_files');
        $model = array('property' => uniqid());
        $widget = new Zle_Widget(
            array('view' => $view, 'partial' => 'foo.phtml', 'model' => $model)
        );
        $this->assertContains(
            'This is the widget content', $widget->render(),
            'Widget should render the given view'
        );
        $this->assertContains(
            $model['property'], $widget->render(),
            'Model should be rendered in the view'
        );
    }
}
