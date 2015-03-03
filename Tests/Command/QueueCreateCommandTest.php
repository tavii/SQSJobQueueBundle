<?php
namespace Tavii\SQSJobQueueBundle\Command;

use Phake;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class QueueCreateCommandTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function キューを作るコマンドを実行する()
    {
        $client = Phake::mock('Aws\Sqs\SqsClient');
        $container = Phake::mock('Symfony\Component\DependencyInjection\Container');

        Phake::when($container)->get('sqs_job_queue.client')->thenReturn($client);


        $application = new Application();
        $application->add(new QueueCreateCommand());

        $command = $application->get('sqs_job_queue:queue-create');
        $command->setContainer($container);

        $tester = new CommandTester($command);
        $tester->execute(array(
            'command' => $command->getName(),
            'queue' => 'test_queue'
        ));

        Phake::verify($container)->get('sqs_job_queue.client');
        Phake::verify($client)->createQueue(array(
            'QueueName' => 'test_queue',
            'Attributes' => array(
                'DelaySeconds' => 0
            ),
        ));


    }
}