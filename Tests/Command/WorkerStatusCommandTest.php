<?php
namespace Tavii\SQSJobQueueBundle\Command;

use Phake;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;


class WorkerStatusCommandTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function コマンドを実行することができる()
    {
        $storage = Phake::mock('Tavii\SQSJobQueue\Storage\DoctrineStorage');
        $container = Phake::mock('Symfony\Component\DependencyInjection\Container');

        Phake::when($storage)->all()->thenReturn(array(
            array(
                'queue' => 'test',
                'server' => 'test.com',
                'proc_id' => 12345,
            )
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