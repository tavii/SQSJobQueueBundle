<?php
namespace Tavii\SQSJobQueueBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Tavii\SQSJobQueue\Queue\QueueName;

class WorkerStartCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('sqs_job_queue:worker-start')
            ->setDescription('Start a sqs worker')
            ->addArgument('queue', InputArgument::REQUIRED, 'Queue name')
            ->addOption('sleep', 'S', InputOption::VALUE_OPTIONAL, 'sleep time', 5)
            ->addOption('foreground', 'f', InputOption::VALUE_NONE, 'Should the worker run in foreground')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $queueName = new QueueName($input->getArgument('queue'), $this->getContainer()->getParameter('sqs_job_queue.prefix'));
        $worker = $this->getContainer()->get('sqs_job_queue.worker');
        $worker->start($queueName, $input->getOption('sleep'));
    }

}