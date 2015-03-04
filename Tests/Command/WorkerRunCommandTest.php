<?php
namespace Tavii\SQSJobQueueBundle\Tests\Command;

use Symfony\Component\Console\Application;
use Phake;
use Symfony\Component\Console\Tester\CommandTester;
use Tavii\SQSJobQueueBundle\Command\WorkerRunCommand;

class WorkerRunCommandTest extends \PHPUnit_Framework_TestCase
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
        $application->add(new WorkerRunCommand());

        $command = $application->get('sqs_job_queue:worker-run');

        $command->setContainer($container);

        $tester = new CommandTester($command);
        $tester->execute(array(
            'command' => $command->getName(),
            'queue' => 'test'
        ));

        Phake::verify($container)->get('sqs_job_queue.worker');
        Phake::verify($worker)->run('test');
    }
}