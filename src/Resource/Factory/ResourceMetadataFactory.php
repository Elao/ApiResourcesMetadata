<?php

/*
 * This file is part of the "elao/api-resources-metadata" package.
 *
 * Copyright (C) 2016 Elao
 *
 * @author Elao <contact@elao.com>
 */

namespace Elao\ApiResourcesMetadata\Resource\Factory;

use Elao\ApiResourcesMetadata\Exception\InvalidArgumentException;
use Elao\ApiResourcesMetadata\Resource\Loader\LoaderInterface;
use Elao\ApiResourcesMetadata\Resource\ResourceIndex;
use Elao\ApiResourcesMetadata\Resource\ResourceMetadata;
use Psr\Cache\CacheItemPoolInterface;

final class ResourceMetadataFactory
{
    /**
     * @internal
     */
    const CACHE_PREFIX = 'elao.api_resources_metadata.metadata.';

    /** @var LoaderInterface[] */
    private $loaders;

    /**
     * Loaded metadata indexed by classname
     *
     * @var ResourceMetadata[]
     */
    private $loadedClasses;

    /** @var ResourceIndex */
    private $resourceIndex;

    /** @var CacheItemPoolInterface|null */
    private $cache;

    /**
     * @param ResourceIndex               $resourceIndex
     * @param LoaderInterface[]           $loaders
     * @param CacheItemPoolInterface|null $cache
     */
    public function __construct(ResourceIndex $resourceIndex, array $loaders, CacheItemPoolInterface $cache = null)
    {
        $this->loaders = $loaders;
        $this->resourceIndex = $resourceIndex;
        $this->loadedClasses = [];
        $this->cache = $cache;
    }

    /**
     * @param $value Resource class, instance, or short name
     *
     * @throws InvalidArgumentException When none of the loaders were able to guess information from
     *
     * @return ResourceMetadata
     */
    public function getMetadataFor($value): ResourceMetadata
    {
        $class = $this->getClass($value);
        $cacheKey = self::CACHE_PREFIX . str_replace('\\', '_', $class);

        if (null !== $this->cache && $this->cache->getItem($cacheKey)->isHit()) {
            return $this->cache->getItem($cacheKey)->get();
        }

        if (isset($this->loadedClasses[$class])) {
            return $this->loadedClasses[$class];
        }

        $metadata = new ResourceMetadata($class);

        $loaded = false;
        foreach ($this->loaders as $loader) {
            if ($loader->loadResourceMetadata($metadata)) {
                $loaded = true;
            }
        }

        if (!$loaded) {
            throw new InvalidArgumentException(sprintf(
                'Unable to load metadata for "%s". No supporting loader found.',
                $class
            ));
        }

        if (null !== $this->cache) {
            $item = $this->cache->getItem($cacheKey);
            $item->set($metadata);
            $this->cache->save($item);
        }

        return $this->loadedClasses[$class] = $metadata;
    }

    /**
     * Gets a class name for a given class, instance or resource short name.
     *
     * @param string|object $value
     *
     * @throws InvalidArgumentException If it isn't a resource
     *
     * @return string The resource class
     */
    private function getClass($value): string
    {
        if (!is_string($value) && !is_object($value)) {
            throw new InvalidArgumentException(sprintf(
                'Cannot create metadata for non-objects. Got: "%s"',
                gettype($value)
            ));
        }

        if (is_string($value)) {
            $value = ltrim($value, '\\');
            if (!class_exists($value) && !interface_exists($value)) {
                // Check for the resource short name
                return $this->resourceIndex->getResourceClass($value);
            }
        }

        if (is_object($value)) {
            $value = get_class($value);
        }

        if (!$this->resourceIndex->has($value)) {
            throw new InvalidArgumentException(sprintf('Class "%s" is not indexed as a resource.', $value));
        }

        return $value;
    }
}
