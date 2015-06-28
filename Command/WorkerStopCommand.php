<?php
namespace Tavii\SQSJobQueueBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class WorkerStopCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('sqs_job_queue:worker-stop')
            ->setDescription('Stop a sqs worker')
            ->addArgument('queue', InputArgument::REQUIRED, 'Queue name')
            ->addOption('pid', 'p', InputOption::VALUE_OPTIONAL, 'stop pid name', null)
            ->addOption('force', 'f', InputOption::VALUE_NONE, 'force storage delete')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $queueName = $this->getContainer()->getParameter('sqs_job_queue.prefix').$input->getArgument('queue');
        $worker = $this->getContainer()->get('sqs_job_queue.worker');
        $worker->stop($queueName, $input->getOption('pid'), $input->getOption('force'));
    }
}