<?php

/*
 * This file is part of the "elao/api-resources-metadata" package.
 *
 * Copyright (C) 2016 Elao
 *
 * @author Elao <contact@elao.com>
 */

namespace Elao\ApiResourcesMetadata\Bridge\Nelmio\ApiDoc\Swagger;

use Elao\ApiResourcesMetadata\Resource\Factory\ResourceMetadataFactory;
use Elao\ApiResourcesMetadata\Resource\ResourceIndex;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Nelmio\ApiDocBundle\Formatter\SwaggerFormatter as NelmioSwaggerFormatter;

/**
 * A special decorator allowing to hook on model name generation
 * and automatically use api resource shortName if available
 */
class SwaggerFormatter extends NelmioSwaggerFormatter
{
    /** @var NelmioSwaggerFormatter */
    private $originalFormatter;

    public function __construct(
        NelmioSwaggerFormatter $originalFormatter,
        string $namingStategy,
        ResourceIndex $resourceIndex,
        ResourceMetadataFactory $metadataFactory
    ) {
        $this->originalFormatter = $originalFormatter;

        $this->originalFormatter->modelRegistry = new ModelRegistry(
            $namingStategy,
            $resourceIndex,
            $metadataFactory
        );
    }

    public function setAuthenticationConfig(array $config)
    {
        $this->originalFormatter->setAuthenticationConfig($config);
    }

    public function format(array $collection, $resource = null)
    {
        return $this->originalFormatter->format($collection, $resource);
    }

    public function produceResourceListing(array $collection)
    {
        return $this->originalFormatter->produceResourceListing($collection);
    }

    protected function getAuthorizations()
    {
        return $this->originalFormatter->getAuthorizations();
    }

    protected function getInfo()
    {
        return $this->originalFormatter->getInfo();
    }

    public function formatOne(ApiDoc $annotation)
    {
        $this->originalFormatter->formatOne($annotation);
    }

    protected function produceApiDeclaration(array $collection, $resource)
    {
        return $this->originalFormatter->produceApiDeclaration($collection, $resource);
    }

    protected function normalizeResourcePath($path)
    {
        return $this->originalFormatter->normalizeResourcePath($path);
    }

    public function setBasePath($path)
    {
        $this->originalFormatter->setBasePath($path);
    }

    protected function deriveQueryParameters(array $input)
    {
        return $this->originalFormatter->deriveQueryParameters($input);
    }

    protected function deriveParameters(array $input, $paramType = 'form')
    {
        return $this->originalFormatter->deriveParameters($input, $paramType);
    }

    public function registerModel($className, array $parameters = null, $description = '')
    {
        return $this->originalFormatter->registerModel($className, $parameters, $description);
    }

    public function setSwaggerVersion($swaggerVersion)
    {
        $this->originalFormatter->setSwaggerVersion($swaggerVersion);
    }

    public function setApiVersion($apiVersion)
    {
        $this->originalFormatter->setApiVersion($apiVersion);
    }

    public function setInfo($info)
    {
        $this->originalFormatter->setInfo($info);
    }

    protected function stripBasePath($basePath)
    {
        return $this->originalFormatter->stripBasePath($basePath);
    }

    protected function generateNickname($method, $resource)
    {
        return $this->originalFormatter->generateNickname($method, $resource);
    }
}
