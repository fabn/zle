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
 * Zle_Mail_Transport_Testing
 *
 * @category Zle
 * @package  Zle_Mail
 * @author   Fabio Napoleoni <f.napoleoni@gmail.com>
 * @license  http://framework.zend.com/license/new-bsd New BSD License
 * @link     http://framework.zend.com/
 */
class Zle_Mail_Transport_Testing extends Zend_Mail_Transport_Abstract
{

    /**
     * Stack of sent emails
     *
     * @var array
     */
    private $_mails = array();

    /**
     * Send an email independent from the used transport
     *
     * The requisite information for the email will be found in the following
     * properties:
     *
     * - {@link $recipients} - list of recipients (string)
     * - {@link $header} - message header
     * - {@link $body} - message body
     *
     * @return void
     */
    protected function _sendMail()
    {
        $this->_mails[] = $this->_mail;
    }

    /**
     * Return number of sent emails by this transport
     *
     * @return int
     */
    public function getSentNumber()
    {
        return count($this->_mails);
    }

    /**
     * Return the stack of sent emails
     *
     * @return array of Zend_Mail objects
     */
    public function getSentEmails()
    {
        return $this->_mails;
    }

    /**
     * Reset stats for this transport instance
     *
     * @return void
     */
    public function reset()
    {
        $this->_mails = array();
    }
}
