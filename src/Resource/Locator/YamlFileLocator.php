<?php

/*
 * This file is part of the "elao/api-resources-metadata" package.
 *
 * Copyright (C) 2016 Elao
 *
 * @author Elao <contact@elao.com>
 */

namespace Elao\ApiResourcesMetadata\Resource\Locator;

use Elao\ApiResourcesMetadata\Exception\InvalidArgumentException;
use Symfony\Component\Yaml\Parser;

class YamlFileLocator implements LocatorInterface
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

    /**
     * {@inheritdoc}
     */
    public function locate(): array
    {
        $parsed = $this->parser->parse(file_get_contents($this->path));
        $resources = $parsed['resources'] ?? [];

        foreach ($resources as $shortName => $resource) {
            if (is_array($resource)) {
                if (!isset($resource['class'])) {
                    throw new InvalidArgumentException(sprintf(
                        'Missing class declaration for resource "%s".',
                        $shortName
                    ));
                }

                $resource = $resource['class'];
            }

            $resources[$shortName] = $resource;
        }

        return $resources;
    }
}
