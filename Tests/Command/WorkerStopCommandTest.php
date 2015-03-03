<?php
namespace Tavii\SQSJobQueueBundle\Command;

use Phake;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class WorkerStopCommandTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function コマンドを実行することができる()
    {
        $worker = Phake::mock('Tavii\SQSJobQueue\Worker\Worker');
        $container = Phake::mock('Symfony\Component\DependencyInjection\Container');

        Phake::when($container)->get('sqs_job_queue.worker')->thenReturn($worker);

        $application = new Application();
        $application->add(new WorkerStopCommand());

        $command = $application->get('sqs_job_queue:worker-stop');

        $command->setContainer($container);

        $tester = new CommandTester($command);
        $tester->execute(array(
            'command' => $command->getName(),
            'queue' => 'test',
        ));

        Phake::verify($container)->get('sqs_job_queue.worker');
        Phake::verify($worker)->stop('test', null);
    }
}