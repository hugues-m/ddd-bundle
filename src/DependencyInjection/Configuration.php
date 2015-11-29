<?php

namespace HMLB\DDDBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Configuration.
 *
 * @author Hugues Maignol <hugues@hmlb.fr>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * Generates the configuration tree.
     *
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('hmlb_ddd');

        $supportedDrivers = [
            'orm',
            'mongodb',
        ];

        $rootNode
            ->children()
            ->scalarNode('db_driver')
            ->defaultValue('orm')
            ->validate()
            ->ifNotInArray($supportedDrivers)
            ->thenInvalid('The driver %s is not supported. Please choose one of '.json_encode($supportedDrivers))
            ->end()
            ->end()
            ->scalarNode('persistence_manager_name')->defaultNull()->end()
            ->booleanNode('persist_commands')->defaultTrue()->end()
            ->booleanNode('persist_events')->defaultTrue()->end()
            ->end();

        return $treeBuilder;
    }
}
