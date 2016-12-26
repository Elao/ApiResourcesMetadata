<?php

/*
 * This file is part of the "elao/api-resources-metadata" package.
 *
 * Copyright (C) 2016 Elao
 *
 * @author Elao <contact@elao.com>
 */

namespace Elao\ApiResourcesMetadata\Resource\Loader;

use Elao\ApiResourcesMetadata\Attribute\ResourceAttributeMetadata;
use Elao\ApiResourcesMetadata\Resource\ResourceMetadata;
use Symfony\Component\Yaml\Parser;

class YamlFileLoader implements LoaderInterface
{
    /** @var string */
    private $path;

    /** @var Parser */
    private $parser;

    public function __construct(string $path)
    {
        $this->path = $path;
        $this->parser = new Parser();
    }

    public function loadResourceMetadata(ResourceMetadata $resourceMetadata): bool
    {
        list($shortName, $resource) = $this->findResourceData($resourceMetadata);

        if (null === $resource) {
            return false;
        }

        $resourceMetadata->setShortName($shortName);

        if (isset($resource['description'])) {
            $resourceMetadata->setDescription($resource['description'] ?? '');
        }

        foreach ($resource['attributes'] ?? [] as $name => $attribute) {
            $attributeMetadata = $resourceMetadata->getAttribute($name) ?? new ResourceAttributeMetadata($name);

            !isset($attribute['description']) ?: $attributeMetadata->setDescription($attribute['description']);
            !isset($attribute['required']) ?: $attributeMetadata->setRequired($attribute['required']);
            !isset($attribute['type']) ?: $attributeMetadata->setType($attribute['type']);
            !isset($attribute['originalType']) ?: $attributeMetadata->setOriginalType($attribute['originalType']);

            $resourceMetadata->addAttribute($attributeMetadata);
        }

        return true;
    }

    private function findResourceData(ResourceMetadata $resourceMetadata)
    {
        $parsed = $this->parser->parse(file_get_contents($this->path));
        $resources = $parsed['resources'] ?? [];

        foreach ($resources as $shortName => $resource) {
            if (is_string($resource)) {
                $resource = ['class' => $resource];
            }

            if ($resourceMetadata->getClass() === $resource['class']) {
                return [$shortName, $resource];
            }
        }

        return null;
    }
}
