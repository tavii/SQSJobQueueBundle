<?php
namespace Tavii\SQSJobQueueBundle;

use Aws\Sqs\SqsClient;
use Tavii\SQSJobQueue\Job\JobInterface;
use Tavii\SQSJobQueue\Message\MessageInterface;
use Tavii\SQSJobQueue\Queue\Queue as BaseQueue;
use Tavii\SQSJobQueue\Queue\QueueInterface;

class Queue implements QueueInterface
{

    /**
     * Aamazon SQS Client
     * @var SqsClient
     */
    private $baseQueue;


    /**
     * @param SqsClient $client
     */
    public function __construct(BaseQueue $baseQueue, array $kernelOptions)
    {
        $this->baseQueue = $baseQueue;
        $this->kernelOptions = $kernelOptions;
    }

    /**
     * {@inheritdoc}
     */
    public function pull($name)
    {
        $message = $this->baseQueue->pull($name);
        $job = $message->getJob();
        if ($job instanceof ContainerAwareJob) {
            $job->setKernelOptions($this->kernelOptions);
        }
        return $message;
    }

    /**
     * {@inheritdoc}
     */
    public function push(JobInterface $job)
    {
        return $this->baseQueue->push($job);
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