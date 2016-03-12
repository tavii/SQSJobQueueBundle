<?php
/**
 * Created by PhpStorm.
 * User: polidog
 * Date: 2016/03/13
 */

namespace Tavii\SQSJobQueueBundle\Event;


use Symfony\Component\EventDispatcher\Event;
use Tavii\SQSJobQueue\Job\JobInterface;

class JobEvent extends Event
{
    /**
     * @var JobInterface
     */
    private $job;

    /**
     * @var boolean
     */
    private $executedStatus;

    /**
     * JobEvent constructor.
     * @param JobInterface $job
     * @param bool $executedStatus
     */
    public function __construct(JobInterface $job)
    {
        $this->job = $job;
        $this->executedStatus = false;
    }

    /**
     * @return JobInterface
     */
    public function getJob()
    {
        return $this->job;
    }

    /**
     * @param boolean $executedStatus
     * @return $this
     */
    public function setExecutedStatus($executedStatus)
    {
        $this->executedStatus = $executedStatus;
        return $this;
    }


    /**
     * @return boolean
     */
    public function isExecutedStatus()
    {
        return $this->executedStatus;
    }



}