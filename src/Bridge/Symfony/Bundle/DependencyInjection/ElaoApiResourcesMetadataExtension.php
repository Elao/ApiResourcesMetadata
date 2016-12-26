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
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\Cache\Adapter\ApcuAdapter;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class ElaoApiResourcesMetadataExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.xml');

        $configuration = $this->getConfiguration($configs, $container);
        $config = $this->processConfiguration($configuration, $configs);

        $arrayLocatorDef = $container->findDefinition('elao.api_resources_metadata.array_locator');
        $arrayLocatorDef->replaceArgument(0, $config['resources']);

        if ($config['nelmio_api_doc']) {
            $bundles = $container->getParameter('kernel.bundles');
            if (!isset($bundles['NelmioApiDocBundle'])) {
                throw new \RuntimeException(sprintf(
                    'Cannot enabled the NelmioApiDoc integration without the %s registered in your kernel.',
                    NelmioApiDocBundle::class
                ));
            }

            $loader->load('nelmio_api_doc.xml');
        }

        if ($config['loaders']['yaml']['enabled']) {
            $loader->load('yaml.xml');

            $path = $config['loaders']['yaml']['path'];

            $loaderDef = $container->findDefinition('elao.api_resources_metadata.yaml_file_loader');
            $loaderDef->replaceArgument(0, $path);

            $locatorDef = $container->findDefinition('elao.api_resources_metadata.yaml_file_locator');
            $locatorDef->replaceArgument(0, $path);
        }

        if ($config['loaders']['property_info']['enabled']) {
            $loader->load('property_info.xml');
            $def = $container->findDefinition('elao.api_resources_metadata.property_info_loader');
            $def->replaceArgument(0, new Reference($config['loaders']['property_info']['service']));
        }

        $this->registerCache($container, $config);
    }

    private function registerCache(ContainerBuilder $container, $config)
    {
        if (isset($config['cache'])) {
            if (false === $config['cache']) {
                return;
            }

            if (true === $config['cache']) {
                return $this->configureDefaultCache($container);
            }

            $container->setAlias('elao.api_resources_metadata.cache', $config['cache']);

            return;
        }

        if ($container->getParameter('kernel.debug') || !interface_exists(AdapterInterface::class)) {
            return;
        }

        $this->configureDefaultCache($container);
    }

    private function configureDefaultCache(ContainerBuilder $container)
    {
        $version = substr(str_replace('/', '-', base64_encode(md5(uniqid(mt_rand(), true), true))), 0, -2);

        if (ApcuAdapter::isSupported()) {
            $cacheDef = $container
                ->register($cacheId = 'elao.api_resources_metadata.cache.apcu', ApcuAdapter::class)
                ->setArguments([null, 0, $version])
            ;
        }

        if (!isset($cacheDef)) {
            $cacheDir = $container->getParameter('kernel.cache_dir') . '/elao.api_resources_meta';
            $cacheDef = $container
                ->register($cacheId = 'elao.api_resources_metadata.cache.filesystem', FilesystemAdapter::class)
                ->setArguments([null, 0, $cacheDir])
            ;
        }

        $cacheDef
            ->setPublic(false)
            ->addMethodCall('setLogger', [new Reference('logger', ContainerInterface::IGNORE_ON_INVALID_REFERENCE)])
            ->addTag('cache.pool', ['clearer' => 'cache.default_clearer'])
            ->addTag('monolog.logger', ['channel' => 'cache'])
        ;

        $container->setAlias('elao.api_resources_metadata.cache', $cacheId);
    }
}
