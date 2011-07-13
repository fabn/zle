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
 * Zle_Mail_Transport_RewriteRecipients
 *
 * @category Zle
 * @package  Zle_Mail
 * @author   Andrea Giannantonio <a.giannantonio@gmail.com>
 * @license  http://framework.zend.com/license/new-bsd New BSD License
 * @link     http://framework.zend.com/
 */
class Zle_Mail_Transport_RewriteRecipients extends Zend_Mail_Transport_Smtp
{

    /** @var array */
    protected $rightRecipients;

    /**
     * @param array $rightRecipients
     */
    public function setRightRecipients($rightRecipients)
    {
        $this->rightRecipients = $rightRecipients;
    }

    /**
     * @return array
     */
    public function getRightRecipients()
    {
        return $this->rightRecipients;
    }

    /** @var array */
    protected $options;

    /**
     * @param array $options
     */
    public function setOptions($options)
    {
        $this->options = $options;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Rewrite Recipients with address developer and send an email via the SMTP connection protocol
     *
     * @return void
     */
    public function _sendMail()
    {
        //save the current recipients
        $this->setRightRecipients($this->_mail->getRecipients());

        //remove recipients
        $this->_mail->clearRecipients();

        //set recipients with addresses developer
        $this->_mail->addTo($this->getAddresses());

        //append to body rightRecipients
        $this->appendToBodyRightRecipients();

        //call parent
        parent::_sendMail();
    }

    /**
     * @return array
     */
    private function getAddresses()
    {
        $resource = new Zend_Application_Resource_ResourceAbstract();
        $this->setOptions($resource->getOptions());
        return $this->options['addresses'];
    }

    /**
     * Append to body the right recipients
     * @return void
     */
    private function appendToBodyRightRecipients()
    {
        //html
        $html = $this->_mail->getBodyHtml(true);
        if (!empty($html)) {
            $html .= "<br/><br/><h3>Right Recipients</h3><ul>";
            foreach ($this->getRightRecipients() as $recipient) {
                $html .= "<li>{$recipient}</li>";
            }
            $html .= "</ul><br/>";
            $this->_mail->setBodyHtml($html);
        }

        //text
        $text = $this->_mail->getBodyText(true);
        if (!empty($text)) {
            $text .= "\n\nRight Recipients";
            foreach ($this->getRightRecipients() as $recipient) {
                $text .= "- {$recipient}\n";
            }
            $text .= "\n";
            $this->_mail->setBodyText($text);
        }
    }
}
