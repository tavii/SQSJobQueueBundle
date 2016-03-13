<?php

namespace Tavii\SQSJobQueueBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class TaviiSQSJobQueueExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('sqs_job_queue.aws.key', $config['aws']['key']);
        $container->setParameter('sqs_job_queue.aws.secret', $config['aws']['secret']);
        $container->setParameter('sqs_job_queue.aws.region', $config['aws']['region']);
        $container->setParameter('sqs_job_queue.prefix', $config['prefix']);

//        $container->addCompilerPass();

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }
}
