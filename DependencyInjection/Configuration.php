<?php

namespace Desarrolla2\Bundle\WebBundle\DependencyInjection;

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
        $rootNode = $treeBuilder->root('web')
                ->children()
                    ->arrayNode('contact')
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('title')->defaultValue('Contact Form')->end()
                            ->scalarNode('name')->defaultValue('Your Name')->end()
                            ->scalarNode('email')->end()             
                        ->end()
                    ->end()                
                ->end();

        return $treeBuilder;
    }
}
