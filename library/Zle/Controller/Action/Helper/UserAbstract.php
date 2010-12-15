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
 * Zle_Controller_Action_Helper_UserAbstract
 *
 * @category Zle
 * @package  Zle_Controller
 * @author   Fabio Napoleoni <f.napoleoni@gmail.com>
 * @license  http://framework.zend.com/license/new-bsd New BSD License
 * @link     http://framework.zend.com/
 */
abstract class Zle_Controller_Action_Helper_UserAbstract
    extends Zend_Controller_Action_Helper_Abstract
{
    /**
     * Auth instance
     * @var Zend_Auth
     */
    private $_auth;

    /**
     * Initialize namespace for auth
     */
    public function __construct()
    {
        $this->_auth = Zend_Auth::getInstance();
        $r = new ReflectionClass($this);
        $this->_auth->setStorage(
            new Zend_Auth_Storage_Session('Zend_Auth_' . $r->getName())
        );
    }

    /**
     * Return current identity or null if user is not found
     *
     * @return mixed an user instance or null if not set
     */
    public function getUser()
    {
        return $this->_auth->getIdentity();
    }

    /**
     * Return current identity or null if user is not found
     *
     * @return mixed an user instance or null if not set
     */
    public function direct()
    {
        return $this->getUser();
    }

    /**
     * Return true iff an user is logged
     *
     * @return bool
     */
    public function isLogged()
    {
        return $this->_auth->hasIdentity();
    }

    /**
     * Store the session id in a persistent way for $days days
     *
     * @param int $days how many days for remember authentication
     *
     * @return void
     */
    public function rememberAuth($days = 60)
    {
        Zend_Session::rememberMe($days * 86400);
    }

    /**
     * Perform an authentication attempt, return true if the user is
     * logged or an array of message in case of errors
     *
     * @param array $values values provided for authentication
     *
     * @return bool|array
     */
    public function login(array $values)
    {
        $adapter = $this->getAdapter();
        if (method_exists($adapter, 'setAuthenticationParameters')) {
            $adapter->setAuthenticationParameters($values);
        }
        // try to authenticate
        $result = $this->_auth->authenticate($adapter);
        // switch result
        return $result->getCode() === Zend_Auth_Result::SUCCESS
                ? true : $result->getMessages();
    }

    /**
     * Return the adapter used for authentication
     *
     * @return Zend_Auth_Adapter_Interface
     */
    abstract protected function getAdapter();

    /**
     * Logout current user
     *
     * @return void
     */
    public function logout()
    {
        $this->_auth->clearIdentity();
        Zend_Session::forgetMe();
    }
}
