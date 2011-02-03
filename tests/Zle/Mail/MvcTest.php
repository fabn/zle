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
 * MvcTest
 *
 * @category Zle
 * @package  Zle_Test
 * @author   Fabio Napoleoni <f.napoleoni@gmail.com>
 * @license  http://framework.zend.com/license/new-bsd New BSD License
 * @link     http://framework.zend.com/
 */
class MvcTest extends PHPUnit_Framework_TestCase
{
    public function testMailIsBuiltWithDefaults()
    {
        $mail = new Zle_Mail_Mvc();
        $this->assertType('Zle_Mail_Mvc', $mail);
    }

    public function testDirectMethodAreDelegatedToView()
    {
        $mail = new Zle_Mail_Mvc();
        $this->assertType('Zend_View', $mail->setHelperPath('foo'));
    }

    public function testUnknownMethodsAreWrappedIntoAnException()
    {
        $mail = new Zle_Mail_Mvc();
        $method = 'unknownMethod';
        try {
            $mail->$method();
            $this->fail('exception not raised');
        } catch (Zle_Mail_Exception $e) {
            $this->assertEquals(
                "Unknown method $method",
                $e->getMessage()
            );
        }
    }
}
