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

    public function testViewIsUsedWhenProvided()
    {
        $mail = $this->getMailObject('index.phtml');
        $mail->buildMessage(true);
        $this->assertContains(
            'index view', quoted_printable_decode($mail->getBodyHtml(true))
        );
    }

    public function testTxtViewIsUsedWhenProvided()
    {
        $mail = $this->getMailObject('', 'index.txt.phtml');
        $mail->buildMessage(true);
        $this->assertContains(
            'index view in txt format',
            quoted_printable_decode($mail->getBodyText(true))
        );
    }

    public function testMessageIsMultipartWhenTxtAndHtmlViewsAreGiven()
    {
        $mail = $this->getMailObject('index.phtml', 'index.txt.phtml');
        $mail->buildMessage(true);
        $this->assertTrue(
            $mail->getBodyHtml(true) && $mail->getBodyText(true),
            'Message should have both html and txt part'
        );
    }

    public function testViewHelpersAreAvailableInTheStandardLocation()
    {
        $mail = $this->getMailObject('helper.phtml');
        $mail->buildMessage(true);
        $this->assertContains(
            'view helper',
            quoted_printable_decode($mail->getBodyHtml(true))
        );
    }

    public function testVariablesAreAssignedToTheView()
    {
        $mail = $this->getMailObject('index.phtml');
        $value = uniqid();
        $mail->view->assign('variable', $value);
        $mail->buildMessage(true);
        $this->assertContains(
            $value, quoted_printable_decode($mail->getBodyHtml(true)),
            'Mail body should contain actual value of variable'
        );
    }

    public function testHtmlLayoutIsUsedWhenProvided()
    {
        $mail = $this->getMailObject('', '', 'html');
        $mail->buildMessage(true);
        $this->assertContains(
            'html layout',
            quoted_printable_decode($mail->getBodyHtml(true))
        );
    }

    public function testTxtLayoutIsUsedWhenProvided()
    {
        $mail = $this->getMailObject('', '', '', 'txt');
        $mail->buildMessage(true);
        $this->assertContains(
            'txt layout',
            quoted_printable_decode($mail->getBodyText(true))
        );
    }

    public function testFullHtmlEmail() {
        $mail = $this->getMailObject('index', '', 'html');
        $mail->buildMessage(true);
        $htmlBody = quoted_printable_decode($mail->getBodyHtml(true));
        $this->assertContains('html layout', $htmlBody);
        $this->assertContains('index view', $htmlBody);
    }

    public function testFullTextEmail() {
        $mail = $this->getMailObject('', 'index.txt', '', 'txt');
        $mail->buildMessage(true);
        $textBody = quoted_printable_decode($mail->getBodyText(true));
        $this->assertContains('txt layout', $textBody);
        $this->assertContains('index view', $textBody);
    }

    /**
     * Build a mail object
     *
     * @param string $htmlView   html view script
     * @param string $txtView    txt view script
     * @param string $htmlLayout html layout script
     * @param string $txtLayout  txt layout script
     *
     * @return Zle_Mail_Mvc
     */
    protected function getMailObject($htmlView = '', $txtView = '', $htmlLayout = '', $txtLayout = '')
    {
        $mail = new Zle_Mail_Mvc();
        $mail->setApplicationPath(
        // set the application path to the testApp provided
            realpath(dirname(__FILE__) . '/_files/mvc')
        );
        if ($htmlView) {
            $mail->setHtmlView($htmlView);
        }
        if ($txtView) {
            $mail->setTxtView($txtView);
        }
        if ($htmlLayout) {
            $mail->setHtmlLayout($htmlLayout);
        }
        if ($txtLayout) {
            $mail->setTxtLayout($txtLayout);
        }
        return $mail;
    }
}
