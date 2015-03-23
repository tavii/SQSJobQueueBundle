<?php
namespace Tavii\SQSJobQueueBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class WorkerStatusCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('sqs_job_queue:worker-status')
            ->setDescription('check the status worker')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // @todo doctrine以外の対応
        $storage = $this->getContainer()->get('sqs_job_queue.storage.doctrine');

        foreach ($storage->all() as $worker) {
            $output->writeln("queue: {$worker->getQueue()}, server: {$worker->getServer()}, pid: {$worker->getProcId()}");
        }
    }
}