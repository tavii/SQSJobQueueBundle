<?php
namespace Tavii\SQSJobQueueBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Tavii\SQSJobQueue\Queue\QueueName;

class WorkerRunCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('sqs_job_queue:worker-run')
            ->setDescription('run a sqs job queue worker')
            ->addArgument('queue', InputArgument::REQUIRED, 'Queue name');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $queueName = new QueueName($input->getArgument('queue'), $this->getContainer()->getParameter('sqs_job_queue.prefix'));
        $worker = $this->getContainer()->get('sqs_job_queue.worker');
        $worker->run($queueName);
    }
}