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
        $aViewFluentMethod = 'setHelperPath';
        $this->assertType('Zend_View', $mail->$aViewFluentMethod('foo'));
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

    public function propertyProvider()
    {
        return array(
            array('htmlLayout', false),
            array('txtLayout', false),
            array('htmlView', true),
            array('txtView', true),
        );
    }

    /**
     * Test suffix behaviour with all the four script methods
     *
     * @dataProvider propertyProvider
     * @param string $propertyName property to test
     * @param bool $suffixExpected expected result flag
     *
     * @return void
     */
    public function testSuffixesBehaviour($propertyName, $suffixExpected)
    {
        $mail = new Zle_Mail_Mvc();
        $setter = 'set' . ucfirst($propertyName);
        $getter = 'get' . ucfirst($propertyName);
        $scriptName = 'foo';
        $scriptNameWithSuffix = 'foo.phtml';
        $expected = $suffixExpected ? $scriptNameWithSuffix : $scriptName;
        $message = $suffixExpected
                ? "Suffix should be added by $getter"
                : "Suffix should not be added by $getter";
        $mail->$setter($scriptName);
        $this->assertEquals($expected, $mail->$getter(), $message);
        $mail->$setter($scriptNameWithSuffix);
        $this->assertEquals($expected, $mail->$getter(), $message);
    }

    public function testApplicationPathMustBeSet()
    {
        $mail = new Zle_Mail_Mvc();
        if (!defined('APPLICATION_PATH')) {
            try {
                $mail->getApplicationPath();
                $this->fail('Expected exception not raised');
            } catch (Zle_Mail_Exception $e) {
                $this->assertEquals(
                    'You must set or define the application path',
                    $e->getMessage()
                );
            }
        }
        $applicationPath = 'fooBar';
        $mail->setApplicationPath($applicationPath);
        $this->assertEquals($applicationPath, $mail->getApplicationPath());
    }
}
