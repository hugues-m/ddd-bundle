<?php

declare (strict_types = 1);

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
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('hmlb_ddd');

        $supportedDrivers = [
            'orm',
            'mongodb',
        ];

        $rootNode
            ->children()
                ->arrayNode('persistence')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('persist_commands')->defaultTrue()->end()
                        ->booleanNode('persist_events')->defaultTrue()->end()
                        ->scalarNode('command_repository_service')
                            ->defaultValue('hmlb_ddd.repository.command.default')
                        ->end()
                        ->scalarNode('event_repository_service')
                            ->defaultValue('hmlb_ddd.repository.event.default')
                        ->end()
                    ->end()
                ->end()
                ->scalarNode('db_driver')
                    ->defaultValue('orm')
                    ->validate()
                        ->ifNotInArray($supportedDrivers)
                        ->thenInvalid(
                            'The driver %s is not supported. Please choose one of '.json_encode($supportedDrivers)
                        )
                    ->end()
                ->end()

            ->end();

        return $treeBuilder;
    }
}
