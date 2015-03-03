<?php
namespace Tavii\SQSJobQueueBundle\Command;

use Phake;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class QueuePurgeCommandTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function キューの中身をクリアするコマンドを実行する()
    {
        $client = Phake::mock('Aws\Sqs\SqsClient');
        $container = Phake::mock('Symfony\Component\DependencyInjection\Container');

        Phake::when($container)->get('sqs_job_queue.client')->thenReturn($client);
        Phake::when($client)->getQueueUrl(array(
            'QueueName' => 'test_queue'
        ))->thenReturn(array(
            'QueueUrl' => '/path/to/url'
        ));

        $application = new Application();
        $application->add(new QueuePurgeCommand());

        $command = $application->get('sqs_job_queue:queue-purge');
        $command->setContainer($container);

        $dialog = $command->getHelper('dialog');
        $dialog->setInputStream($this->getInputStream("yes\n"));

        $tester = new CommandTester($command);
        $tester->execute(array(
            'command' => $command->getName(),
            'queue' => 'test_queue'
        ));

        Phake::verify($container)->get('sqs_job_queue.client');
        Phake::verify($client)->purgeQueue(array(
            'QueueUrl' => '/path/to/url',
        ));
    }

    protected function getInputStream($input)
    {
        $stream = fopen('php://memory', 'r+', false);
        fputs($stream, $input);
        rewind($stream);

        return $stream;
    }
}