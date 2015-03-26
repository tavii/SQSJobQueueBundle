<?php
namespace Tavii\SQSJobQueueBundle;

use Tavii\SQSJobQueue\Job\JobInterface;
use Tavii\SQSJobQueue\Message\MessageInterface;
use Tavii\SQSJobQueue\Queue\Queue as BaseQueue;
use Tavii\SQSJobQueue\Queue\QueueInterface;

class Queue implements QueueInterface
{

    /**
     * @var Queue
     */
    private $baseQueue;


    /**
     * @param BaseQueue $baseQueue
     * @param array $kernelOptions
     */
    public function __construct(BaseQueue $baseQueue, array $kernelOptions)
    {
        $this->baseQueue = $baseQueue;
        $this->kernelOptions = $kernelOptions;
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
            return $message;
        }
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function send(JobInterface $job)
    {
        return $this->baseQueue->send($job);
    }

    /**
     * {@inheritdoc}
     */
    public function delete(MessageInterface $message)
    {
        if ($this->baseQueue->delete($message)) {
            $job = $message->getJob();
            unset($job);
            return true;
        }
        return false;
    }


}