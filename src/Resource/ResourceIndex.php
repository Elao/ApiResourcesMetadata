<?php

/*
 * This file is part of the "elao/api-resources-metadata" package.
 *
 * Copyright (C) 2016 Elao
 *
 * @author Elao <contact@elao.com>
 */

namespace Elao\ApiResourcesMetadata\Resource;

use Elao\ApiResourcesMetadata\Exception\InvalidArgumentException;
use Elao\ApiResourcesMetadata\Resource\Locator\LocatorInterface;
use Psr\Cache\CacheItemPoolInterface;

class ResourceIndex
{
    /**
     * @internal
     */
    const CACHE_KEY = 'elao.api_resources_metadata.indexed_resources';

    /** @var LocatorInterface[] */
    private $resourceLocators;

    /** @var array */
    private $resourcesCache;

    /** @var CacheItemPoolInterface|null */
    private $cache;

    public function __construct(array $resourceLocators, CacheItemPoolInterface $cache = null)
    {
        $this->resourceLocators = $resourceLocators;
        $this->cache = $cache;
    }

    /**
     * @return array resources classes indexed by short name
     */
    public function all(): array
    {
        $this->locateResources();

        return $this->resourcesCache;
    }

    /**
     * @param string $resource Resource class or short name
     *
     * @return bool True if the class or short name identifies an existing resource
     */
    public function has(string $resource): bool
    {
        return isset($this->all()[$resource]) || in_array($resource, $this->all(), true);
    }

    /**
     * @param string $resourceClass The resource FQCN
     *
     * @throws InvalidArgumentException When no resource found
     *
     * @return string The resource short name
     */
    public function getShortName(string $resourceClass): string
    {
        if (!$this->has($resourceClass)) {
            throw new InvalidArgumentException(sprintf('Class "%s" is not indexed as a resource.', $resourceClass));
        }

        return array_search($resourceClass, $this->all(), true);
    }

    /**
     * @param string $shortName The resource short name
     *
     * @throws InvalidArgumentException When no resource found
     *
     * @return string The resource FQCN
     */
    public function getResourceClass(string $shortName): string
    {
        if (!$this->has($shortName)) {
            throw new InvalidArgumentException(sprintf('"%s" is not indexed as a resource.', $shortName));
        }

        return $this->all()[$shortName];
    }

    private function locateResources()
    {
        if (null !== $this->resourcesCache) {
            return $this->resourcesCache;
        }

        if (null !== $this->cache && $this->cache->getItem(self::CACHE_KEY)->isHit()) {
            return $this->resourcesCache = $this->cache->getItem(self::CACHE_KEY)->get();
        }

        $this->resourcesCache = [];

        foreach ($this->resourceLocators as $locator) {
            $this->resourcesCache += $locator->locate();
        }

        if (null !== $this->cache) {
            $item = $this->cache->getItem(self::CACHE_KEY);
            $item->set($this->resourcesCache);
            $this->cache->save($item);
        }
    }
}
