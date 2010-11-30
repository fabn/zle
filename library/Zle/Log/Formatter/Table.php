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
 * Zle_Log_Formatter_Table
 *
 * @category Zle
 * @package  Zle_Log
 * @author   Fabio Napoleoni <f.napoleoni@gmail.com>
 * @license  http://framework.zend.com/license/new-bsd New BSD License
 * @link     http://framework.zend.com/
 */
class Zle_Log_Formatter_Table implements Zend_Log_Formatter_Interface
{

    /**
     * Interface implementation
     *
     * @param array $event event data
     *
     * @return string formatted line to write to the log
     */
    public function format($event)
    {
        $eventInfo = sprintf(
            '<tr><td>%s</td><td>%s (%d)</td><td>%s</td></tr>' . PHP_EOL,
            $event['timestamp'], $event['priorityName'],
            $event['priority'], $event['message']
        );
        if (isset($event['info']) && $event['info'] instanceof Exception) {
            $eventInfo .= '<tr><td colspan="3">';
            $eventInfo .= $this->formatException($event['info']);
            $eventInfo .= '</td></tr>';
        }
        return $eventInfo;
    }

    /**
     * Format an exception using an internal script
     *
     * @param Exception $e exception to be formatted
     *
     * @return string
     */
    protected function formatException(Exception $e)
    {
        $view = new Zend_View(array('scriptPath' => dirname(__FILE__)));
        return $view->partial('exception.phtml', array('exception' => $e));
    }
}
