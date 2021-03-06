<?php
namespace Tavii\SQSJobQueueBundle\Tests;


use Tavii\SQSJobQueueBundle\ContainerAwareJob;

class ContainerAwareJobTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function コンテナを取得することができる()
    {
        $job = new TestJob();
        $job->setKernelOptions(array(
            'kernel.root_dir' => __DIR__,
            'kernel.debug' => false,
            'kernel.environment' => 'test'
        ));

        $actual = $job->run();
        $this->assertInstanceOf('Symfony\Component\DependencyInjection\ContainerInterface', $actual);
    }

}

class TestJob extends ContainerAwareJob
{

    protected $name = 'name';

    public function run()
    {
        return $this->getContainer();
    }

}

