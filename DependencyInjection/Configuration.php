<?php

namespace Tavii\SQSJobQueueBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('sqs_job_queue');

        $rootNode->children()
            ->arrayNode('aws')
                ->children()
                    ->scalarNode('key')->end()
                    ->scalarNode('secret')->end()
                    ->scalarNode('region')->defaultValue('ap-northeast-1')->end()
                ->end()
            ->end()
            ;

        return $treeBuilder;
    }
}
