<?php
namespace Tavii\SQSJobQueueBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class QueuePurgeCommand extends  ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('sqs_job_queue:queue-purge')
            ->setDescription('list queue')
            ->addArgument('queue', InputArgument::REQUIRED, 'queue name')
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $client = $this->getContainer()->get('sqs_job_queue.client');
        $queue = $input->getArgument('queue');
        $result = $client->getQueueUrl(array(
            'QueueName' => $queue
        ));

        $dialog = $this->getHelper('dialog');
        $ret = $dialog->askConfirmation(
            $output,
            "<question>{$queue} purge queue?[yes or no]</question> ",
            false
        );

        if ($ret) {
            $client->purgeQueue(array(
                'QueueUrl' => $result['QueueUrl']
            ));
            $output->writeln('<info>pugrge queue url</info>: '. $result['QueueUrl']);
        } else {
            $output->writeln('cancel purge...');
        }

    }
}