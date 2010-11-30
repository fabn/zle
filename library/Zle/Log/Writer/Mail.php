<?php

/**
 * Zend Library Extension
 *
 * PHP version 5
 *
 * @category Zle
 * @package  Zle_Log
 * @author   Fabio Napoleoni <f.napoleoni@gmail.com>
 * @license  http://framework.zend.com/license/new-bsd New BSD License
 * @link     http://framework.zend.com/
 */

/**
 * Zle_Log_Writer_Mail
 *
 * @category Zle
 * @package  Zle_Log
 * @author   Fabio Napoleoni <f.napoleoni@gmail.com>
 * @license  http://framework.zend.com/license/new-bsd New BSD License
 * @link     http://framework.zend.com/
 */
class Zle_Log_Writer_Mail extends Zend_Log_Writer_Mail
{
    /**
     * @var array options default
     */
    private $_options = array(
        'sender' => null,
        'priority' => Zend_Log::ERR,
        'project' => null,
        'subject' => 'Errors in project %s',
    );

    /**
     * Build a log writer which write to the provided email addresses
     *
     * @param array $options an array of options for the log writer,
     * allowed values are (with their default value)
     * - addresses: string or array of email addresses (required)
     * - sender:    email address for the from header
     *              (default: null {@see Zend_Mail::setDefaultFrom})
     * - priority:  a priority filter, (default = Zend_Log::ERR)
     * - project:   an identifier for the current project (default = null)
     * - subject:   a string automatically prepended to subject
     *              (default = 'Errors in project $name')
     *              {@see Zend_Log_Writer_Mail::setSubjectPrependText}
     */
    public function __construct(array $options = array())
    {
        // parse options
        $this->setOptions($options);
        // build a new mail object
        $mail = new Zend_Mail('utf-8');
        if (isset($this->_options['sender'])) {
            $mail->setFrom($this->_options['sender']);
        }
        if (is_string($this->_options['addresses'])) {
            $mail->addTo($this->_options['addresses']);
        } else {
            foreach ($this->_options['addresses'] as $email) {
                $mail->addTo($email);
            }
        }
        parent::__construct($mail, $this->getLayout());
        $this->setLayoutFormatter(new Zle_Log_Formatter_Table());
        $this->setSubjectPrependText($this->_options['subject']);
        $this->addFilter($this->_options['priority']);
    }

    /**
     * Set the options for notifier, see constructor documentation
     *
     * @param array $options options to set
     *
     * @return void
     */
    protected function setOptions(array $options)
    {
        // check for address existence
        if (!isset($options['addresses'])
            || (!is_string($options['addresses'])
                && !is_array($options['addresses']))
        ) {
            throw new Zend_Log_Exception(
                'At least an email address must be provided'
            );
        }
        $this->_options['addresses'] = $options['addresses'];
        // check for sender
        if (isset($options['sender'])) {
            $this->_options['sender'] = $options['sender'];
        }
        // check for subject or project name
        if (isset($options['subject'])) {
            $this->_options['subject'] = $options['subject'];
        } elseif (isset($options['project'])) {
            $this->_options['subject'] = sprintf(
                $this->_options['subject'], $options['project']
            );
        }
    }

    /**
     * Return the layout for the email log
     *
     * @return Zend_Layout
     */
    protected function getLayout()
    {
        $layout = new Zend_Layout(dirname(__FILE__));
        $layout->setLayout('log-email');
        return $layout;
    }
}
