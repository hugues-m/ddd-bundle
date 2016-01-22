<?php

namespace HMLB\DDDBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * HMLBDDDExtension.
 *
 * @author Hugues Maignol <hugues@hmlb.fr>
 */
class HMLBDDDExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): array
    {
        $processor = new Processor();
        $configuration = new Configuration();

        $config = $processor->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        if ($config['db_driver']) {
            $loader->load(sprintf('%s.xml', $config['db_driver']));
            $container->setParameter($this->getAlias().'.backend_type_'.$config['db_driver'], true);
        }

        foreach (['messages'] as $basename) {
            $loader->load(sprintf('%s.xml', $basename));
        }

        $this->remapParameters(
            $config,
            $container,
            [
                'persist_commands' => 'hmlb_ddd.persist_commands',
                'persist_events' => 'hmlb_ddd.persist_events',
            ]
        );

        $this->remapParametersNamespaces(
            $config,
            $container,
            [
                '' => [
                    'db_driver' => 'hmlb_ddd.db_driver',
                    'persistence_manager_name' => 'hmlb_ddd.persistence_manager_name',
                ],
            ]
        );

        return $config;
    }

    private function remapParameters(array $config, ContainerBuilder $container, array $map)
    {
        foreach ($map as $name => $paramName) {
            if (array_key_exists($name, $config)) {
                $container->setParameter($paramName, $config[$name]);
            }
        }
    }

    private function remapParametersNamespaces(array $config, ContainerBuilder $container, array $namespaces)
    {
        foreach ($namespaces as $ns => $map) {
            if ($ns) {
                if (!array_key_exists($ns, $config)) {
                    continue;
                }
                $namespaceConfig = $config[$ns];
            } else {
                $namespaceConfig = $config;
            }
            if (is_array($map)) {
                $this->remapParameters($namespaceConfig, $container, $map);
            } else {
                foreach ($namespaceConfig as $name => $value) {
                    $container->setParameter(sprintf($map, $name), $value);
                }
            }
        }
    }

    public function getAlias(): string
    {
        return 'hmlb_ddd';
    }
}
