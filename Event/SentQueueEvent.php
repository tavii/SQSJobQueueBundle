<?php
/**
 * Created by PhpStorm.
 * User: polidog
 * Date: 2016/03/13
 */

namespace Tavii\SQSJobQueueBundle\Event;


use Symfony\Component\EventDispatcher\Event;
use Tavii\SQSJobQueue\Job\JobInterface;

class SentQueueEvent extends Event
{
    /**
     * @var JobInterface
     */
    private $job;


    /**
     * @var boolean
     */
    private $sent;

    /**
     * SentQueueEvent constructor.
     * @param JobInterface $job
     * @param bool $sent
     */
    public function __construct(JobInterface $job, $sent)
    {
        $this->job = $job;
        $this->sent = $sent;
    }


    /**
     * @return boolean
     */
    public function isSent()
    {
        return $this->sent;
    }

    /**
     * @return JobInterface
     */
    public function getJob()
    {
        return $this->job;
    }



}