<?php
namespace Tavii\SQSJobQueueBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class StorageInitCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('sqs_job_queue:storage-init')
            ->setDescription('create storage')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // @todo doctrine以外の対応
        $storage = $this->getContainer()->get('sqs_job_queue.storage.doctrine');
        $storage->create();
        $output->writeln('database table create sqs_workers');
    }
}