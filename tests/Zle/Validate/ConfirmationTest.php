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
 * ConfirmationTest
 *
 * @category Zle
 * @package  Zle_Test
 * @author   Fabio Napoleoni <f.napoleoni@gmail.com>
 * @license  http://framework.zend.com/license/new-bsd New BSD License
 * @link     http://framework.zend.com/
 */
class ConfirmationTest extends PHPUnit_Framework_TestCase
{
    /**
     * @param string $key key to hold the confirm
     *
     * @return Zle_Validate_Confirmation
     */
    protected function getValidator($key = 'password_confirm')
    {
        return new Zle_Validate_Confirmation($key);
    }

    public function testIsValidThrowAnExceptionWithNoContext()
    {
        try {
            $this->getValidator()->isValid('foo');
            $this->fail('Expected exception not raised');
        } catch (Zend_Validate_Exception $e) {
            $this->assertEquals('$context should be an array', $e->getMessage());
        }
    }

    public function testIsValidThrowAnExceptionWithNoKeyInContext()
    {
        $key = 'foobar';
        try {
            $this->getValidator($key)->isValid('foo', array());
            $this->fail('Expected exception not raised');
        } catch (Zend_Validate_Exception $e) {
            $this->assertEquals(
                "\$context does not have $key element given in the constructor",
                $e->getMessage()
            );
        }
    }

    public function testValidatorIsWorkingWithRightConfirm()
    {
        $key = 'foo';
        $value = 'bar';
        $this->assertTrue(
            $this->getValidator($key)->isValid($value, array($key => $value)),
            'Validator should return true with valid confirmation'
        );
    }

    public function testValidatorIsWorkingWithWrongConfirm()
    {
        $key = 'foo';
        $value = 'bar';
        $validator = $this->getValidator($key);
        $this->assertFalse(
            $validator->isValid($value, array($key => 'wrong')),
            'Validator should return false with invalid confirmation'
        );
        $this->assertArrayHasKey(
            Zle_Validate_Confirmation::NOT_MATCH,
            $validator->getMessages(),
            'Errors should be set correctly'
        );
    }
}
