<?php

/**
 * Zend Library Extension
 *
 * PHP version 5
 *
 * @category Zle
 * @package  Zle_Mail
 * @author   Fabio Napoleoni <f.napoleoni@gmail.com>
 * @license  http://framework.zend.com/license/new-bsd New BSD License
 * @link     http://framework.zend.com/
 */

/**
 * Zle_Mail_Transport_RewriteRecipients, this class could be useful in development
 * to prevent actual mails to be sent. Configure it with application.ini file with
 *
 * @category Zle
 * @package  Zle_Mail
 * @author   Andrea Giannantonio <a.giannantonio@gmail.com>
 * @license  http://framework.zend.com/license/new-bsd New BSD License
 * @link     http://framework.zend.com/
 */
class Zle_Mail_Transport_RewriteRecipients extends Zend_Mail_Transport_Smtp
{

    /**
     * Whether or not Zle_Mail_Transport_RewriteRecipients is being used with unit tests
     *
     * @internal
     * @var bool
     */
    public static $_unitTestEnabled = false;

    /**
     * Constructor. Called by application.ini
     *
     * @param array $config OPTIONAL (Default: null)
     *
     * @return \Zle_Mail_Transport_RewriteRecipients
     */
    public function __construct(array $config = array())
    {

    }

    /**
     * @var array recipients container
     */
    protected $actualRecipients;

    /**
     * Recipients setter
     *
     * @param array $recipients an array of alternative recipients
     *
     * @return void
     */
    public function setActualRecipients(array $recipients)
    {
        $this->actualRecipients = $recipients;
    }

    /**
     * Recipients getter
     *
     * @return array
     */
    public function getActualRecipients()
    {
        return $this->actualRecipients;
    }

    /**
     * Rewrite Recipients with configured address(es) and send the email
     * using the SMTP connection protocol configured in the base class
     *
     * @return void
     */
    public function _sendMail()
    {
        // save original recipients
        $originalRecipients = $this->_mail->getRecipients();

        // remove original recipients
        $this->_mail->clearRecipients();
        // add rewritten ones
        foreach ($this->getActualRecipients() as $address) {
            $this->_mail->addTo($address);
        }
        // rewrite mail body
        $this->_appendOriginalRecipientsToBody($originalRecipients);
        // call parent
        parent::_sendMail();
    }

    /**
     * Return sent email with this transport
     *
     * @throws Zle_Exception if used without unit test enabled
     *
     * @return array an array of sent emails
     */
    public function getSentEmails()
    {

    }

    /**
     * Append to body the original recipients of the message
     *
     * @param array $recipients an array of addresses
     *
     * @return void
     */
    private function _appendOriginalRecipientsToBody(array $recipients)
    {
        //html
        $html = $this->_mail->getBodyHtml(true);
        if (!empty($html)) {
            $html .= "<br/><br/><h3>Right Recipients</h3><ul>";
            foreach ($this->getRecipients() as $recipient) {
                $html .= "<li>{$recipient}</li>";
            }
            $html .= "</ul><br/>";
            $this->_mail->setBodyHtml($html);
        }

        //text
        $text = $this->_mail->getBodyText(true);
        if (!empty($text)) {
            $text .= "\n\nRight Recipients";
            foreach ($this->getRecipients() as $recipient) {
                $text .= "- {$recipient}\n";
            }
            $text .= "\n";
            $this->_mail->setBodyText($text);
        }
    }
}
