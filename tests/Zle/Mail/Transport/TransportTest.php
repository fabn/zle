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
 * TransportTest
 *
 * @category Zle
 * @package  Zle_
 * @author   Fabio Napoleoni <f.napoleoni@gmail.com>
 * @license  http://framework.zend.com/license/new-bsd New BSD License
 * @link     http://framework.zend.com/
 */
class TransportTest extends PHPUnit_Framework_TestCase
{

    /**
     * Return a new zend mail instance
     *
     * @return Zend_Mail
     */
    protected function getMail()
    {
        $mail = new Zend_Mail();
        $mail->setSubject('Subject');
        $mail->setBodyText('Body');
        return $mail;
    }

    public function testMailAreNotSentWithTestTransport()
    {
        $transport = new Zle_Mail_Transport_Testing();
        $mail = $this->getMail();
        $mail->send($transport);
        $this->assertEquals(1, $transport->getSentNumber());
        $this->assertEquals(
            $mail, current($transport->getSentEmails()),
            'Mail object should be returned by getSentEmails'
        );
    }

    public function testTransportResetMethod()
    {
        $transport = new Zle_Mail_Transport_Testing();
        $mail = $this->getMail();
        $mail->send($transport);
        $transport->reset();
        $this->assertEquals(0, $transport->getSentNumber());
    }
}
