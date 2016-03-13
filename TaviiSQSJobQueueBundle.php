<?php

namespace Tavii\SQSJobQueueBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\EventDispatcher\DependencyInjection\RegisterListenersPass;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class TaviiSQSJobQueueBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new RegisterListenersPass("event_dispatcher", "sqs_job_queue.event_listener", "sqs_job_queue.event_subscriber"));
    }
}
