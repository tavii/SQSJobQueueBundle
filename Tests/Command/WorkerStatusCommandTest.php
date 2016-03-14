<?php
namespace Tavii\SQSJobQueueBundle\Tests\Command;

use Phake;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Tavii\SQSJobQueue\Queue\QueueName;
use Tavii\SQSJobQueue\Storage\EntityInterface;
use Tavii\SQSJobQueue\Storage\EntityJobNameTrait;
use Tavii\SQSJobQueueBundle\Command\WorkerStatusCommand;


class WorkerStatusCommandTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function コマンドを実行することができる()
    {
        $storage = Phake::mock('Tavii\SQSJobQueueBundle\Storage\DoctrineStorage');
        $container = Phake::mock('Symfony\Component\DependencyInjection\Container');

        $entity = new TestEntity('test','test.com', 12345);


        Phake::when($storage)->all()->thenReturn(array(
            $entity
        ));
        Phake::when($container)->get('sqs_job_queue.storage.doctrine')->thenReturn($storage);

        $application = new Application();
        $application->add(new WorkerStatusCommand());

        $command = $application->get('sqs_job_queue:worker-status');

        $command->setContainer($container);

        $tester = new CommandTester($command);
        $tester->execute(array(
            'command' => $command->getName()
        ));

        Phake::verify($container)->get('sqs_job_queue.storage.doctrine');
        Phake::verify($storage)->all();
    }
}


class TestEntity implements EntityInterface
{
    use EntityJobNameTrait;

    private $queue;

    private $server;

    private $procId;

    private $prefix;


    public function __construct($queue, $server, $procId)
    {
        $this->queue = $queue;
        $this->server = $server;
        $this->procId = $procId;
        $this->prefix = "test";

    }


    /**
     * @return string
     */
    public function getQueue()
    {
        return $this->queue;
    }

    /**
     * @return string
     */
    public function getServer()
    {
        return $this->server;
    }

    /**
     * @return int
     */
    public function getProcId()
    {
        return $this->procId;
    }

    public function getPrefix()
    {
        return $this->prefix;
    }


}