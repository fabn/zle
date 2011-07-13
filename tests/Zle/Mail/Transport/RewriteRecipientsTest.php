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
 * RewriteRecipientsTest
 *
 * @category Zle
 * @package  Zle_Test
 * @author   Andrea Giannantonio <a.giannantonio@gmail.com>
 * @license  http://framework.zend.com/license/new-bsd New BSD License
 * @link     http://framework.zend.com/
 */
class RewriteRecipientsTest extends PHPUnit_Framework_TestCase
{
    protected $resourceOptions = array(
        'resources' => array(
            'mail' => array(
                'transport' => array(
                    'type' => 'RewriteRecipients',
                    'addresses' => array('user1@example.com', 'user2@example.com'),
                    'host' => 'mail.example.com',
                    'auth' => 'login',
                    'username' => 'user@example.com',
                    'password' => 'secret',
                ),
            ),
        ),
    );

    /** @var Zend_Application */
    protected $application;

    /** @var Zend_Application_Bootstrap_Bootstrap */
    protected $bootstrap;

    /**
     * @return void
     */
    protected function setUp()
    {
        // initialize an app and a bootstrap
        $this->application = new Zend_Application('testing');
        $this->bootstrap = new Zend_Application_Bootstrap_Bootstrap($this->application);
        $this->bootstrap->setOptions($this->resourceOptions);
    }

    /**
     * Return a new zend mail instance
     *
     * @return Zend_Mail
     */
    protected function getMail()
    {
        $mail = new Zend_Mail();
        $mail->setSubject('Subject');
        $mail->setBodyHtml('Body HTML');
        $mail->setBodyText('Body Text');
        $mail->addTo('user3@example.com');
        return $mail;
    }

    public function testMailAreNotSentWithTestTransportCheckRewriteRecipients()
    {
        $transport = new Zle_Mail_Transport_Testing();
        $mail = $this->getMail();
        $mail->send($transport);
        $this->assertEquals(1, $transport->getSentNumber());
        $this->assertContains('user1@example.com', $mail->getBodyHtml(true));
        $this->assertTrue(in_array('user2@example.com', $mail->getRecipients()));
    }

}
