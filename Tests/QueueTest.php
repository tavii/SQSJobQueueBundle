<?php
namespace Tavii\SQSJobQueueBundle;

use Phake;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Tavii\SQSJobQueue\Job\booelan;
use Tavii\SQSJobQueue\Message\Message;
use Tavii\SQSJobQueue\Queue\QueueName;

class QueueTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function receiveする際にKernelOptionを渡すことができる()
    {
        $name = "test_queue";
        $queueName = new QueueName($name);
        $kernelOptions = array(
            'kernel.root_dir' => '.',
            'kernel.environment' => 'test',
            'kernel.debug' => true,
        );

        $job = Phake::mock('Tavii\SQSJobQueueBundle\ContainerAwareJob');
        $message = Phake::mock('Tavii\SQSJobQueue\Message\Message');
        $baseQueue = Phake::mock('Tavii\SQSJobQueue\Queue\Queue');
        $dispatcher = Phake::mock(EventDispatcherInterface::class);

        Phake::when($baseQueue)->receive($queueName)
            ->thenReturn($message);
        Phake::when($message)->getJob()
            ->thenReturn($job);

        $queue = new Queue($baseQueue, $dispatcher, $kernelOptions);
        $queue->receive($queueName);


        Phake::verify($baseQueue)->receive($queueName);
        Phake::verify($message)->getJob();
        Phake::verify($job)->setKernelOptions($kernelOptions);

    }

    /**
     * @test
     */
    public function queueに登録することが出来る()
    {
        $kernelOptions = array(
            'kernel.root_dir' => '.',
            'kernel.environment' => 'test',
            'kernel.debug' => true,
        );

        $baseQueue = Phake::mock('Tavii\SQSJobQueue\Queue\Queue');
        $dispatcher = Phake::mock(EventDispatcherInterface::class);

        $queue = new Queue($baseQueue, $dispatcher, $kernelOptions);

        $job = new DummyContainerAwareJob();
        $queue->send($job);

        Phake::verify($baseQueue)->send($job);
    }

    /**
     * @test
     */
    public function 削除を行う事ができる()
    {
        $kernelOptions = array(
            'kernel.root_dir' => '.',
            'kernel.environment' => 'test',
            'kernel.debug' => true,
        );
        $dispatcher = Phake::mock(EventDispatcherInterface::class);


        $baseQueue = Phake::mock('Tavii\SQSJobQueue\Queue\Queue');
        $queue = new Queue($baseQueue, $dispatcher, $kernelOptions);

        $job = new DummyContainerAwareJob();
        $message = new Message(array(),$job,'test.com');

        Phake::when($baseQueue)->delete($message)
            ->thenReturn(true);

        $job = new DummyContainerAwareJob();
        $queue->delete($message);

        Phake::verify($baseQueue)->delete($message);
    }
}

class DummyContainerAwareJob extends ContainerAwareJob
{
    protected $name = 'dummy_container_aware_job';

    /**
     * @return booelan
     */
    protected function run()
    {

    }

}