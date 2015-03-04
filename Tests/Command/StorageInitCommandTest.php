<?php
namespace Tavii\SQSJobQueueBundle\Tests\Command;

use Phake;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Tavii\SQSJobQueueBundle\Command\StorageInitCommand;

class StorageInitCommandTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function コマンドを実行することができる()
    {
        $storage = Phake::mock('Tavii\SQSJobQueue\Storage\DoctrineStorage');
        $container = Phake::mock('Symfony\Component\DependencyInjection\Container');

        Phake::when($container)->get('sqs_job_queue.storage.doctrine')->thenReturn($storage);

        $application = new Application();
        $application->add(new StorageInitCommand());

        $command = $application->get('sqs_job_queue:storage-init');

        $command->setContainer($container);

        $tester = new CommandTester($command);
        $tester->execute(array(
            'command' => $command->getName()
        ));

        Phake::verify($container)->get('sqs_job_queue.storage.doctrine');
        Phake::verify($storage)->create();
    }
}