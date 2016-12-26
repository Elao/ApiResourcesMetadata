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
use Nelmio\ApiDocBundle\Swagger\ModelRegistry as NelmioModelRegistry;

/**
 * A special ModelRegistry allowing to hook on model name generation
 * and automatically use api resource shortName if available
 */
class ModelRegistry extends NelmioModelRegistry
{
    /** @var ResourceMetadataFactory */
    private $metadataFactory;

    /** @var ResourceIndex */
    private $resourceIndex;

    public function __construct($namingStrategy, ResourceIndex $resourceIndex, ResourceMetadataFactory $metadataFactory)
    {
        parent::__construct($namingStrategy);

        $this->metadataFactory = $metadataFactory;
        $this->resourceIndex = $resourceIndex;
    }

    public function register($className, array $parameters = null, $description = '')
    {
        if ('' === $className) {
            // Fix an issue with a ghost model registered by Nelmio
            return;
        }

        $id = parent::register($className, $parameters, $description);

        $matches = [];
        if (preg_match('/^(.*)\[(.*)\]$/', $className, $matches)) {
            list(, $class, $itemsName) = $matches;
            if (null !== $shortName = $this->getShortName($class)) {
                $collectionShortName = sprintf('%s[%s]', $shortName, $itemsName);
                $this->models[$collectionShortName] = $this->models[$id];
                $this->models[$collectionShortName]['id'] = $collectionShortName;
                $this->models[$collectionShortName]['description'] = $this->metadataFactory->getMetadataFor($class)->getDescription() ?: "array of objects ($shortName)";
                unset($this->models[$id]);

                return $collectionShortName;
            }
        }

        if (null !== $shortName = $this->getShortName($className)) {
            $this->models[$shortName] = $this->models[$id];
            $this->models[$shortName]['id'] = $shortName;
            $this->models[$shortName]['description'] = $this->metadataFactory->getMetadataFor($className)->getDescription() ?: "object ($shortName)";
            unset($this->models[$id]);

            return $shortName;
        }

        return $id;
    }

    public function getShortName(string $className)
    {
        return $this->resourceIndex->has($className) ? $this->resourceIndex->getShortName($className) : null;
    }
}
