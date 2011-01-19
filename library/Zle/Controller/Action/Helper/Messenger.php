<?php

/**
 * Zend Library Extension
 *
 * PHP version 5
 *
 * @category Zle
 * @package  Zle_Controller
 * @author   Fabio Napoleoni <f.napoleoni@gmail.com>
 * @license  http://framework.zend.com/license/new-bsd New BSD License
 * @link     http://framework.zend.com/
 */

/**
 * Zle_Controller_Action_Helper_Messenger
 *
 * @category Zle
 * @package  Zle_Controller
 * @author   Fabio Napoleoni <f.napoleoni@gmail.com>
 * @license  http://framework.zend.com/license/new-bsd New BSD License
 * @link     http://framework.zend.com/
 */
class Zle_Controller_Action_Helper_Messenger
    extends Zend_Controller_Action_Helper_FlashMessenger
{

    /**
     * @var array Immediate messages
     */
    private $_immediateMessages = array();

    /**
     * Override flashMessenger storing messages in session
     *
     * @param string $message  a textual message
     * @param int    $priority a Zend_Log priority for the message
     */
    public function direct($message, $priority = Zend_Log::INFO)
    {
        return parent::direct($this->_buildMessage($message, $priority));
    }

    /**
     * Store messages for immediate usage
     *
     * @param string $message  a textual message
     * @param int    $priority a Zend_Log priority for the message
     */
    public function immediate($message, $priority = Zend_Log::INFO)
    {
        array_push($this->_immediateMessages,
                   $this->_buildMessage($message, $priority));
    }

    /**
     * Return immediate messages merged with session messages
     *
     * @return array
     */
    public function getMessages()
    {
        return array_merge(parent::getMessages(), $this->_immediateMessages);
    }

    /**
     * @param $message text message
     * @param $errorMsg if true the message will be highlighted as error
     */
    protected function _buildMessage($message, $priority = Zend_Log::INFO)
    {
        $msg = new stdClass();
        $msg->content = $message;
        $msg->priority = $priority;
        return $msg;
    }
}
