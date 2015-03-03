<?php
namespace Tavii\SQSJobQueueBundle\Command;

use Phake;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class QueueListCommandTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function キュー一覧を取得するコマンドを実行する()
    {
        $client = Phake::mock('Aws\Sqs\SqsClient');
        $container = Phake::mock('Symfony\Component\DependencyInjection\Container');

        Phake::when($container)->get('sqs_job_queue.client')->thenReturn($client);
        Phake::when($client)->listQueues($this->isType('array'))
            ->thenReturn(array(
                'QueueUrls' => array('/path/to/url'),
            ));

        $application = new Application();
        $application->add(new QueueListCommand());

        $command = $application->get('sqs_job_queue:queue-list');
        $command->setContainer($container);

        $tester = new CommandTester($command);
        $tester->execute(array(
            'command' => $command->getName(),
            '--queueNamePrefix' => 'test'
        ));

        Phake::verify($container)->get('sqs_job_queue.client');
        Phake::verify($client)->listQueues(array(
            'QueueNamePrefix' => 'test'
        ));
    }
}