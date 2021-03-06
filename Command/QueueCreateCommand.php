<?php
namespace Tavii\SQSJobQueueBundle\Command;


use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Tavii\SQSJobQueue\Queue\QueueName;

class QueueCreateCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('sqs_job_queue:queue-create')
            ->setDescription('create queue')
            ->addArgument('queue', InputArgument::REQUIRED, 'queue name')
            ->addOption('delaySec', 'S', InputOption::VALUE_OPTIONAL, 'DelaySeconds', 0)
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $prefix = $this->getContainer()->getParameter('sqs_job_queue.prefix');
        $client = $this->getContainer()->get('sqs_job_queue.client');

        $queueName = new QueueName($input->getArgument('queue'), $this->getContainer()->getParameter('sqs_job_queue.prefix'));
        $client->createQueue(array(
            'QueueName' => $queueName->getQueueName(),
            'Attributes' => array(
                'DelaySeconds' => $input->getOption('delaySec')
            ),
        ));
        $output->writeln('<info>SUCCESS:</info> '.$queueName->getQueueName());
    }
}