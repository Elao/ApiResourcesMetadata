<?php

/*
 * This file is part of the "elao/api-resources-metadata" package.
 *
 * Copyright (C) 2016 Elao
 *
 * @author Elao <contact@elao.com>
 */

namespace Elao\ApiResourcesMetadata\Bridge\Symfony\Bundle\DependencyInjection;

use Nelmio\ApiDocBundle\NelmioApiDocBundle;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $root = $treeBuilder->root('elao_api_resources_metadata');

        $root
            ->children()
                ->booleanNode('nelmio_api_doc')->defaultValue(class_exists(NelmioApiDocBundle::class))->end()
                ->scalarNode('cache')->info('true, false or cache service id')->end()
                // loaders
                ->arrayNode('loaders')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('yaml')
                            ->canBeDisabled()
                            ->children()
                                ->scalarNode('path')
                                    ->defaultValue('%kernel.root_dir%/Resources/api_resources_metadata.yml')
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('property_info')
                            ->canBeDisabled()
                            ->children()
                                ->scalarNode('service')
                                    ->defaultValue('elao.api_resources_metadata.property_info')
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
                // resources for array locator
                ->arrayNode('resources')
                    ->useAttributeAsKey('shortName')->prototype('scalar')->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
