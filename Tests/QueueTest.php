<?php
namespace Tavii\SQSJobQueueBundle;

use Phake;
use Tavii\SQSJobQueue\Job\booelan;
use Tavii\SQSJobQueue\Message\Message;

class QueueTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function receiveする際にKernelOptionを渡すことができる()
    {
        $name = "test_queue";
        $kernelOptions = array(
            'kernel.root_dir' => '.',
            'kernel.environment' => 'test',
            'kernel.debug' => true,
        );

        $job = Phake::mock('Tavii\SQSJobQueueBundle\ContainerAwareJob');
        $message = Phake::mock('Tavii\SQSJobQueue\Message\Message');
        $baseQueue = Phake::mock('Tavii\SQSJobQueue\Queue\Queue');

        Phake::when($baseQueue)->receive($name)
            ->thenReturn($message);
        Phake::when($message)->getJob()
            ->thenReturn($job);

        $queue = new Queue($baseQueue, $kernelOptions);
        $queue->receive($name);

        Phake::verify($baseQueue)->receive($name);
        Phake::verify($message)->getJob();
        Phake::verify($job)->setKernelOptions($kernelOptions);

    }
}

class DummyContainerAwareJob extends ContainerAwareJob
{
    /**
     * @return booelan
     */
    public function run()
    {

    }

    /**
     * @return string
     */
    public function getName()
    {
       return 'dummy_container_aware_job';
    }

}