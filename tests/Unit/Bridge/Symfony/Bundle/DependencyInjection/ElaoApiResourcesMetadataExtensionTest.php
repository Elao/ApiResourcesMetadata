<?php

/*
 * This file is part of the "elao/api-resources-metadata" package.
 *
 * Copyright (C) 2016 Elao
 *
 * @author Elao <contact@elao.com>
 */

namespace Elao\ApiResourcesMetadata\Tests\Unit\Bridge\Symfony\Bundle\DependencyInjection;

use Elao\ApiResourcesMetadata\Bridge\Symfony\Bundle\DependencyInjection\ElaoApiResourcesMetadataExtension;
use Nelmio\ApiDocBundle\NelmioApiDocBundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ElaoApiResourcesMetadataExtensionTest extends \PHPUnit_Framework_TestCase
{
    /** @var ContainerBuilder */
    private $container;

    /** @var ElaoApiResourcesMetadataExtension */
    private $extension;

    protected function setUp()
    {
        $this->extension = new ElaoApiResourcesMetadataExtension();
        $this->container = new ContainerBuilder();
        $this->container->getParameterBag()->add([
            'kernel.debug' => true,
            'kernel.cache_dir' => 'cache_dir',
            'kernel.bundles' => [
                'NelmioApiDocBundle' => NelmioApiDocBundle::class,
            ],
        ]);
    }

    public function testNelmioApiDocIntegrationCanBeEnabled()
    {
        $this->extension->load([['nelmio_api_doc' => true]], $this->container);

        $this->assertTrue($this->container->has('elao.api_resources_metadata.nelmio_api_doc.parser'));
        $this->assertTrue($this->container->has('elao.api_resources_metadata.nelmio_api_doc.extractor'));
        $this->assertTrue($this->container->has('elao.api_resources_metadata.nelmio_api_doc.swagger_formatter'));
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Cannot enabled the NelmioApiDoc integration without the Nelmio\ApiDocBundle\NelmioApiDocBundle registered in your kernel.
     */
    public function testThrowsExceptionOnNelmioApiDocBundleNotRegistered()
    {
        $this->container->setParameter('kernel.bundles', []);

        $this->extension->load([['nelmio_api_doc' => true]], $this->container);
    }

    public function testCacheCanBeConfigured()
    {
        $cacheDef = $this->container->register('foo_cache_service', \stdClass::class);

        $this->extension->load([[
            'cache' => 'foo_cache_service',
        ]], $this->container);

        $this->assertCacheConfigured();

        $this->assertSame($cacheDef, $this->container->findDefinition('elao.api_resources_metadata.cache'));
    }

    public function testCacheCanBeDisabled()
    {
        $this->extension->load([['cache' => false]], $this->container);

        $this->assertFalse($this->container->hasAlias('elao.api_resources_metadata.cache'));
    }

    public function testDefaultCacheIsConfiguredOnTrue()
    {
        $this->extension->load([['cache' => true]], $this->container);

        $this->assertCacheConfigured();
    }

    public function testConfigureDefaultCacheWhenNotDebug()
    {
        $this->container->setParameter('kernel.debug', false);
        $this->extension->load([[
            'cache' => null,
        ]], $this->container);

        $this->assertCacheConfigured();
    }

    private function assertCacheConfigured()
    {
        $this->assertTrue($this->container->hasAlias('elao.api_resources_metadata.cache'));

        $this->assertSame(
            'elao.api_resources_metadata.cache',
            (string) $this->container->findDefinition('elao.api_resources_metadata.resource_metadata_factory')->getArgument(2)
        );
        $this->assertSame(
            'elao.api_resources_metadata.cache',
            (string) $this->container->findDefinition('elao.api_resources_metadata.resource_index')->getArgument(1)
        );
    }
}
