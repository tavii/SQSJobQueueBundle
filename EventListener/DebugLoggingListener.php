<?php

/**
 * Created by PhpStorm.
 * User: polidog
 * Date: 2016/03/13
 */

namespace Tavii\SQSJobQueueBundle\EventListener;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Tavii\SQSJobQueueBundle\Event\DeleteQueueEvent;
use Tavii\SQSJobQueueBundle\Event\JobEvent;
use Tavii\SQSJobQueueBundle\Event\ReceiveQueueEvent;
use Tavii\SQSJobQueueBundle\Event\SentQueueEvent;
use Tavii\SQSJobQueueBundle\SQSJobQueueEvents;

/**
 * Class LoggingListener
 */
class DebugLoggingListener implements EventSubscriberInterface
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * LoggingListener constructor.
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }


    /**
     * @param ReceiveQueueEvent $event
     */
    public function onQueueReceived(ReceiveQueueEvent $event)
    {
        $this->logger->debug("Queue Received: ",[
            'queue_url' => $event->getMessage()->getQueueUrl(),
            'job_name' => $event->getMessage()->getJob()->getName(),
        ]);
    }

    /**
     * @param SentQueueEvent $event
     */
    public function onQueueSent(SentQueueEvent $event)
    {
        $this->logger->debug("Queue Send: ",[
            'job_name' => $event->getJob()->getName(),
            'sent' => $event->isSent()
        ]);
    }

    /**
     * @param DeleteQueueEvent $event
     */
    public function onQueueDeleted(DeleteQueueEvent $event)
    {
        $this->logger->debug("Queue Delete: ",[
            'queue_url' => $event->getMessage()->getQueueUrl(),
            'job_name' => $event->getMessage()->getJob()->getName(),
            'deleted' => $event->isDeleted()
        ]);

    }

    /**
     * @param JobEvent $event
     */
    public function onJobExecute(JobEvent $event)
    {
        $this->logger->debug("Job Execute: ",[
            'job_name' => $event->getJob()->getName(),
        ]);
    }

    /**
     * @param JobEvent $event
     */
    public function onJobRan(JobEvent $event)
    {
        $this->logger->debug("Job Ran: ",[
            'job_name' => $event->getJob()->getName(),
            'execute_status' => $event->isExecutedStatus()
        ]);

    }

    public static function getSubscribedEvents()
    {
        return [
            SQSJobQueueEvents::QUEUE_RECEIVED => 'onQueueReceived',
            SQSJobQueueEvents::QUEUE_SENT => 'onQueueSent',
            SQSJobQueueEvents::QUEUE_DELETED => 'onQueueDeleted',
            SQSJobQueueEvents::JOB_EXECUTE => 'onJobExecute',
            SQSJobQueueEvents::JOB_RAN => 'onJobRan',
        ];
    }

}