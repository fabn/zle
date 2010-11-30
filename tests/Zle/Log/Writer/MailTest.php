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
 * MailTest
 *
 * @category Zle
 * @package  Zle_Test
 * @author   Fabio Napoleoni <f.napoleoni@gmail.com>
 * @license  http://framework.zend.com/license/new-bsd New BSD License
 * @link     http://framework.zend.com/
 */
class MailTest extends PHPUnit_Framework_TestCase
{

    private $_oldTransport;

    private $_oldFrom;

    /**
     * @var Zle_Mail_Transport_Testing
     */
    private $_transport;

    protected function setUp()
    {
        // backup static vars
        $this->_oldTransport = Zend_Mail::getDefaultTransport();
        $this->_oldFrom = Zend_Mail::getDefaultFrom();
        // set mail transport to testing
        Zend_Mail::setDefaultTransport(
            $this->_transport = new Zle_Mail_Transport_Testing()
        );
    }

    protected function tearDown()
    {
        // restore static vars
        if ($this->_oldTransport !== null) {
            Zend_Mail::setDefaultTransport($this->_oldTransport);
        }
        Zend_Mail::setDefaultFrom($this->_oldFrom['email'], $this->_oldFrom['name']);
    }

    /**
     * @return Zend_Mail
     */
    protected function getGeneratedEmail($options = array())
    {
        $writer = new Zle_Log_Writer_Mail($options);
        $log = new Zend_Log($writer);
        $log->crit('Foo message');
        // cause writer shutdown and log events
        unset($log);
        $this->assertEquals(
            1, $this->_transport->getSentNumber(),
            'One message should be sent'
        );
        return current($this->_transport->getSentEmails());
    }

    public function optionsProvider()
    {
        $data = array();
        $data[] = array(array());
        $data[] = array(array('address' => 234));
        $data[] = array(array('address' => array()));
        return $data;
    }

    /**
     * Verify that an email address should exist in options
     *
     * @dataProvider optionsProvider
     */
    public function testAddressMustBePresentInOptions($options)
    {
        try {
            new Zle_Log_Writer_Mail($options);
            $this->fail();
        } catch (Exception $e) {
            $this->assertContains('At least an email address', $e->getMessage());
        }
    }

    public function testSetAddressUsingOptions()
    {
        $address = 'foo@example.org';
        $mail = $this->getGeneratedEmail(array('addresses' => $address));
        $this->assertEquals(array($address), $mail->getRecipients());
    }

    public function testSetAddressesUsingOptions()
    {
        $addresses = array('foo@example.org', 'foobar@example.org');
        $mail = $this->getGeneratedEmail(array('addresses' => $addresses));
        $this->assertEquals($addresses, $mail->getRecipients());
    }

    public function testSetSenderUsingOptions()
    {
        $sender = 'foobar@example.org';
        $mail = $this->getGeneratedEmail(
            array('addresses' => 'foo@example.org', 'sender' => $sender)
        );
        $this->assertEquals($sender, $mail->getFrom());
    }

    public function testSetProjectNameUsingOptions()
    {
        $project = 'Foo project';
        $mail = $this->getGeneratedEmail(
            array('addresses' => 'foo@example.org', 'project' => $project)
        );
        $this->assertContains($project, $mail->getSubject());
    }

    public function testSetSubjectUsingOptions()
    {
        $subject = 'List of errors';
        $mail = $this->getGeneratedEmail(
            array('addresses' => 'foo@example.org', 'subject' => $subject)
        );
        $this->assertStringStartsWith($subject, $mail->getSubject());
    }

    public function testVerifyDefaults()
    {
        Zend_Mail::setDefaultFrom('foobar@example.org');
        $mail = $this->getGeneratedEmail(array('addresses' => 'foo@example.org'));
        $this->assertEquals('foobar@example.org', $mail->getFrom());
        $this->assertStringStartsWith('Errors in project', $mail->getSubject());
    }
}
