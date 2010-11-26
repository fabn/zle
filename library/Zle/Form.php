<?php

/**
 * Zend Library Extension
 *
 * PHP version 5
 *
 * @category Zle
 * @package  Zle_Form
 * @author   Fabio Napoleoni <f.napoleoni@gmail.com>
 * @license  http://framework.zend.com/license/new-bsd New BSD License
 * @link     http://framework.zend.com/
 */

/**
 * Zle_Form
 *
 * @category Zle
 * @package  Zle_Form
 * @author   Fabio Napoleoni <f.napoleoni@gmail.com>
 * @license  http://framework.zend.com/license/new-bsd New BSD License
 * @link     http://framework.zend.com/
 */
abstract class Zle_Form extends Zend_Form
{

    /**
     * Form title used in some decorators
     * @var string
     */
    private $_title;

    /**
     * Set form title
     *
     * @param string $value the title for this form
     *
     * @return Zle_Form
     */
    public function setTitle($value)
    {
        $this->_title = (string) $value;
        return $this;
    }

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
     * Implements initialization of form, set common behaviours through forms
     *
     * @see Zend_Form::init()
     *
     * @return void
     */
    public function init()
    {
        // set the form to be post
        $this->setMethod($this->getFormType());
        // add path for elements
        $this->addPrefixPath('Zle_Form_Element', 'Zle/Form/Element/', 'element');
        // add path for validators
        $this->addElementPrefixPath('Zle_Validate', 'Zle/Validate/', 'validate');
        if (($ns = $this->getNamespace())) {
            // add path for elements
            $this->addPrefixPath(
                "{$ns}_Form_Element", "$ns/Form/Element/", 'element'
            );
            // add path for validators
            $this->addElementPrefixPath(
                "{$ns}_Validate", "$ns/Validate/", 'validate'
            );
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
     * Add a confirmation code to the form using the {@link Zend_Form_Element_Hash}
     * useful to avoid CSRF attack and prevent resubmission of forms
     *
     * @return void
     */
    protected function addConfirmationCode()
    {
        $class = new Zend_Reflection_Class($this);
        $name = $class->getName() . '_confirmcode';
        $this->addElement(
            'hash', $name,
            array('decorators' => array('viewHelper'), 'ignore' => true)
        );
    }

    /**
     * Build form elements in the subclasses, subclasses will create form elements
     * in this method, called in the middle of the init method, before adding
     * filters and decorators
     *
     * @return void
     */
    abstract protected function initComponents();

    /**
     * Return the namespace for subclasses, if provided and it's not null
     * the form will add the namespace for elements and validators under
     * the given namespace using the Zend Framework convention
     *
     * @return string
     */
    protected function getNamespace()
    {
        return null;
    }

    /**
     * Used to return form type for submission, default to POST
     *
     * @return string
     */
    protected function getFormType()
    {
        return self::METHOD_POST;
    }

    /**
     * Add an hidden field to the form without any decorator
     *
     * @param string $name  Name of the hidden field
     * @param string $value Value for the hidden field
     *
     * @return void
     */
    public function addHidden($name, $value)
    {
        $hidden = new Zend_Form_Element_Hidden($name, array('value' => $value));
        $hidden->clearDecorators();
        $hidden->addDecorator('ViewHelper');
        $this->addElement($hidden);
    }
}
