<?php
namespace Tavii\SQSJobQueueBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class QueueDeleteCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('sqs_job_queue:queue-delete')
            ->setDescription('list queue')
            ->addArgument('queue', InputArgument::REQUIRED, 'queue name')
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $prefix = $this->getContainer()->getParameter('sqs_job_queue.prefix');
        $client = $this->getContainer()->get('sqs_job_queue.client');
        $queue = $input->getArgument('queue');
        $result = $client->getQueueUrl(array(
            'QueueName' => $prefix.$queue
        ));

        $dialog = $this->getHelper('dialog');
        $ret = $dialog->askConfirmation(
            $output,
            "<question>{$queue} delete queue?[yes or no]</question> ",
            true
        );

        if ($ret) {
            $client->deleteQueue(array(
                'QueueUrl' => $result['QueueUrl']
            ));
            $output->writeln('<info>delete queue url</info>: '. $result['QueueUrl']);
        } else {
            $output->writeln('cancel purge...');
        }
    }

}