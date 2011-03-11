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
 * DoctrineProfilerFirebug
 *
 * @category Zle
 * @package  Zle_Controller
 * @author   Fabio Napoleoni <f.napoleoni@gmail.com>
 * @license  http://framework.zend.com/license/new-bsd New BSD License
 * @link     http://framework.zend.com/
 */
class Zle_Controller_Plugin_DoctrineProfilerFirebug
    extends Zend_Controller_Plugin_Abstract
{
    /**
     * The original label for this profiler.
     * @var string
     */
    protected $label = 'Zle_Db_Profiler_Firebug';

    /**
     * The label template for this profiler
     * @var string
     */
    protected $label_template = '%label% (%totalCount% @ %totalDuration% sec)';

    /**
     * The message envelope holding the profiling summary
     * @var Zend_Wildfire_Plugin_FirePhp_TableMessage
     */
    protected $message = null;

    /**
     * The total time taken for all profiled queries.
     * @var float
     */
    protected $totalElapsedTime = 0;

    /**
     * Number of executed queries
     * @var int
     */
    public $totalNumQueries = 0;

    /**
     * The doctrine profiler object
     * @var Doctrine_Connection_Profiler
     */
    protected $profiler;

    /**
     * Constructor
     */
    public function __construct()
    {
        $conn = Doctrine_Manager::connection();
        $conn->setListener($this->profiler = new Doctrine_Connection_Profiler());
        // setup firebug
        $this->message = new Zend_Wildfire_Plugin_FirePhp_TableMessage($this->label);
        $this->message->setBuffered(true);
        $this->message->setHeader(array('Time', 'Event', 'Parameters'));
        $this->message->setDestroy(true);
        $this->message->setOption('includeLineNumbers', false);
        Zend_Wildfire_Plugin_FirePhp::getInstance()->send($this->message);
    }

    /**
     * Called before Zend_Controller_Front exits its dispatch loop.
     *
     * @return void
     */
    public function dispatchLoopShutdown()
    {
        foreach ($this->profiler as $event) {
            /** @var $event Doctrine_Event */
            $this->recordEvent($event);
        }
        // update label
        $this->updateMessageLabel();
    }


    /**
     * Insert a record in the queries table
     *
     * @param Doctrine_Event $event the event to log
     *
     * @throws Zend_Db_Profiler_Exception
     *
     * @return void
     */
    public function recordEvent($event)
    {
        $this->message->setDestroy(false);
        // update time counter
        $this->totalElapsedTime += $event->getElapsedSecs();
        // add a row to the table
        $this->message->addRow(
            array((string)round($event->getElapsedSecs(), 5),
                 $event->getQuery() ? $event->getQuery() : $event->getName(),
                 ($params = $event->getParams()) ? $params : null)
        );
        // increment number of queries
        $this->totalNumQueries++;
    }

    /**
     * Update the label of the message holding the profile info.
     *
     * @return void
     */
    protected function updateMessageLabel()
    {
        $this->message->setLabel(
            str_replace(
                array('%label%', '%totalCount%', '%totalDuration%'),
                array($this->label,
                     $this->totalNumQueries,
                     (string)round($this->totalElapsedTime, 5)
                ),
                $this->label_template
            )
        );
    }
}
