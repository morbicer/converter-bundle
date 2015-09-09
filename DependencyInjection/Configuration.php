<?php

namespace Morbicer\ConverterBundle\DependencyInjection;

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
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('morbicer_converter');

        $rootNode
            ->children()
                ->scalarNode('default_provider')->defaultValue('chain')->cannotBeEmpty()->end()
                ->arrayNode('providers')
                    ->children()
                        ->arrayNode('google')
                        ->end()
                        ->arrayNode('yahoo')
                        ->end()
                        ->arrayNode('currency_api')
                            ->prototype('scalar')->end()
                        ->end()
                        ->arrayNode('chain')
                            ->prototype('scalar')->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
