<?php
namespace Tavii\SQSJobQueueBundle\Command;


use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class QueueListCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('sqs_job_queue:queue-list')
            ->setDescription('list queue')
            ->addOption('queueNamePrefix', 'Q',InputOption::VALUE_OPTIONAL, 'QueueNamePrefix', '')
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $client = $this->getContainer()->get('sqs_job_queue.client');
        $results = $client->listQueues(array(
            'QueueNamePrefix' => $input->getOption('queueNamePrefix'),
        ));

        foreach ($results['QueueUrls'] as $queueUrl) {
            $output->writeln("<info>queue url:</info> $queueUrl");
        }
    }
}