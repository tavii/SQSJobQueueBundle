<?php

namespace Tavii\SQSJobQueueBundle;


use Tavii\SQSJobQueue\Job\Job;

abstract class ContainerAwareJob extends Job
{
    public function setKernelOptionh(array $options = array())
    {
        $this->args = \array_merge($this->args, $options);
    }
}