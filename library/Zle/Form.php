<?php

class Zle_Form extends Zend_Form
{

    /**
     * Implements initialization of form, set common behaviours through forms
     * @see Zend_Form::init()
     *
     * @return void
     */
    public function init()
    {
        // set the form to be post
        $this->setMethod($this->getFormType());
        // add path for elements
        $this->addPrefixPath('Zle_Form_Element', 'Zle/Form/Element/',
                        'element');
        // add path for validators
        $this->addElementPrefixPath('Zle_Validate', 'Zle/Validate/',
                        'validate');
        if (($ns = $this->getNamespace())) {
            // add path for elements
            $this->addPrefixPath("$ns_Form_Element", "$ns/Form/Element/", 'element');
            // add path for validators
            $this->addElementPrefixPath("$ns_Validate", "$ns/Validate/",
                            'validate');
        }
        // call abstract method that init form elements
        $this->initComponents();
        // add a confirmation code to the form
        $this->addConfirmationCode();
        // trim all values provided in form
        foreach ($this->getElements() as $elt) {
            $elt->addFilter('StringTrim');
        }
    }

    /**
     * Add a confirmation code to the form
     */
    protected function addConfirmationCode()
    {
        $class = new Zend_Reflection_Class($this);
        $name = $class->getName() . '_confirmcode';
        $this->addElement('hash', $name,
                        array('decorators' => array('viewHelper'), 'ignore' => true));
    }

    /**
     * @abstract subclass will create form elements in this method,
     * called in the middle of the init method, before adding filters and decoratorss
     */
    abstract protected function initComponents();

    abstract protected function getNamespace();

    protected function getFormType()
    {
        return 'post';
    }

    /**
     * Add an hidden field to the form without any decorator
     *
     * @param string $name
     * @param string $value
     */
    public function addHidden($name, $value)
    {
        $hidden = new Zend_Form_Element_Hidden($name, array('value' => $value));
        $hidden->clearDecorators();
        $hidden->addDecorator('ViewHelper');
        $this->addElement($hidden);
    }

    /**
     * Form title
     * @var string
     */
    protected $_title;

    /**
     * Return form title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->_title;
    }

    /**
     * Set form title
     *
     * @param  string $value
     * @return Zend_Form
     */
    public function setTitle($value)
    {
        $this->_title = (string) $value;
        return $this;
    }
}
