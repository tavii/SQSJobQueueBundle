<?php
namespace Tavii\SQSJobQueueBundle;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpKernel\KernelInterface;
use Tavii\SQSJobQueue\Job\Job;
use Tavii\SQSJobQueueBundle\Event\JobEvent;

abstract class ContainerAwareJob extends Job
{
    private $kernel;

    protected $name;

    public function __destruct()
    {
        if ($this->kernel instanceof KernelInterface) {
            $this->kernel->shutdown();
        }
    }

    /**
     * @return bool
     */
    public function execute()
    {
        $event = new JobEvent($this);
        $this->getEventDispatcher()->dispatch(SQSJobQueueEvents::JOB_EXECUTE, $event);
        $result = $this->run();
        $event->setExecutedStatus($result);
        $this->getEventDispatcher()->dispatch(SQSJobQueueEvents::JOB_RUN, $event);
        return $result;
    }

    /**
     * @param array $options
     */
    public function setKernelOptions(array $options = array())
    {
        $this->args = array_merge($this->args, $options);
    }

    /**
     * @return ContainerInterface
     */
    protected function getContainer()
    {
        if (! $this->kernel instanceof KernelInterface) {
            $this->kernel = $this->createKernel();
            $this->kernel->boot();
        }
        return $this->kernel->getContainer();
    }

    /**
     * @return KernelInterface
     */
    protected function createKernel()
    {
        $finder = new Finder();
        $finder->name('*Kernel.php')->depth(0)->in($this->args['kernel.root_dir']);
        $results = iterator_to_array($finder);
        $file = current($results);
        $class = $file->getBasename('.php');
        require_once $file;
        return new $class(
            isset($this->args['kernel.environment']) ? $this->args['kernel.environment'] : 'dev',
            isset($this->args['kernel.debug']) ? $this->args['kernel.debug'] : true
        );
    }

    /**
     * @return string
     */
    final public function getName()
    {
        $prefix = $this->getContainer()->getParameter('sqs_job_queue.prefix');
        return $prefix.$this->name;
    }

    final protected function getEventDispatcher()
    {
        return $this->getContainer()->get('event_dispatcher');
    }

}