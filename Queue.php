<?php
namespace Tavii\SQSJobQueueBundle;

use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Tavii\SQSJobQueue\Job\JobInterface;
use Tavii\SQSJobQueue\Message\MessageInterface;
use Tavii\SQSJobQueue\Queue\QueueInterface;
use Tavii\SQSJobQueueBundle\Event\ReceiveQueueEvent;
use Tavii\SQSJobQueueBundle\Event\SentQueueEvent;

class Queue implements QueueInterface
{

    /**
     * @var Queue
     */
    private $baseQueue;

    /**
     * @var array
     */
    private $kernelOptions;

    /**
     * @var EventDispatcher
     */
    private $dispatcher;


    /**
     * Queue constructor.
     * @param QueueInterface $baseQueue
     * @param EventDispatcherInterface $dispatcher
     * @param array $kernelOptions
     */
    public function __construct(QueueInterface $baseQueue, EventDispatcherInterface $dispatcher, array $kernelOptions)
    {
        $this->baseQueue = $baseQueue;
        $this->kernelOptions = $kernelOptions;
        $this->dispatcher = $dispatcher;
    }

    /**
     * {@inheritdoc}
     */
    public function receive($name)
    {
        $message = $this->baseQueue->receive($name);
        if ($message instanceof MessageInterface) {
            $job = $message->getJob();
            if ($job instanceof ContainerAwareJob) {
                $job->setKernelOptions($this->kernelOptions);
            }
            $this->dispatcher->dispatch(SQSJobQueueEvents::QUEUE_RECEIVED, new ReceiveQueueEvent($message));
            return $message;
        }
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function send(JobInterface $job)
    {
        if ($job instanceof ContainerAwareJob) {
            $job->setKernelOptions($this->kernelOptions);
        }
        $result = $this->baseQueue->send($job);
        $this->dispatcher->dispatch(SQSJobQueueEvents::QUEUE_SENT, new SentQueueEvent($job, $result));
        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(MessageInterface $message)
    {
        $result = false;
        if ($this->baseQueue->delete($message)) {
            $job = $message->getJob();
            unset($job);
            $result = true;
        }
        return $result;
    }


}