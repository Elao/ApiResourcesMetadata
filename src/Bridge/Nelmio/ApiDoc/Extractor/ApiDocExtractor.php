<?php

/*
 * This file is part of the "elao/api-resources-metadata" package.
 *
 * Copyright (C) 2016 Elao
 *
 * @author Elao <contact@elao.com>
 */

namespace Elao\ApiResourcesMetadata\Bridge\Nelmio\ApiDoc\Extractor;

use Elao\ApiResourcesMetadata\Resource\ResourceIndex;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Nelmio\ApiDocBundle\DataTypes;
use Nelmio\ApiDocBundle\Extractor\ApiDocExtractor as NelmioApiDocExtractor;
use Nelmio\ApiDocBundle\Parser\ParserInterface;
use Symfony\Component\Routing\Route;

/**
 * Decorates an existing NelmioApiDocExtractor instance.
 */
class ApiDocExtractor extends NelmioApiDocExtractor
{
    /** @var NelmioApiDocExtractor */
    private $innerApiDocExtractor;

    /** @var ResourceIndex */
    private $resourceIndex;

    public function __construct(NelmioApiDocExtractor $innerApiDocExtractor, ResourceIndex $resourceIndex)
    {
        $this->innerApiDocExtractor = $innerApiDocExtractor;
        $this->resourceIndex = $resourceIndex;
    }

    protected function generateHumanReadableType($actualType, $subType)
    {
        if (null === $shortName = $this->getShortName($subType)) {
            return $this->innerApiDocExtractor->generateHumanReadableType($actualType, $subType);
        }

        if ($actualType == DataTypes::MODEL) {
            return sprintf('object (%s)', $shortName);
        }

        if ($actualType == DataTypes::COLLECTION) {
            return sprintf('array of objects (%s)', $shortName);
        }
    }

    public function getRoutes()
    {
        return $this->innerApiDocExtractor->getRoutes();
    }

    public function all($view = ApiDoc::DEFAULT_VIEW)
    {
        return $this->innerApiDocExtractor->all($view);
    }

    public function extractAnnotations(array $routes, $view = ApiDoc::DEFAULT_VIEW)
    {
        return $this->innerApiDocExtractor->extractAnnotations($routes, $view);
    }

    public function getReflectionMethod($controller)
    {
        return $this->innerApiDocExtractor->getReflectionMethod($controller);
    }

    public function get($controller, $route)
    {
        return $this->innerApiDocExtractor->get($controller, $route);
    }

    public function addParser(ParserInterface $parser)
    {
        $this->innerApiDocExtractor->addParser($parser);
    }

    protected function extractData(ApiDoc $annotation, Route $route, \ReflectionMethod $method)
    {
        return $this->innerApiDocExtractor->extractData($annotation, $route, $method);
    }

    protected function normalizeClassParameter($input)
    {
        return $this->innerApiDocExtractor->normalizeClassParameter($input);
    }

    protected function mergeParameters($p1, $p2)
    {
        return $this->innerApiDocExtractor->mergeParameters($p1, $p2);
    }

    protected function parseAnnotations(ApiDoc $annotation, Route $route, \ReflectionMethod $method)
    {
        $this->innerApiDocExtractor->parseAnnotations($annotation, $route, $method);
    }

    protected function clearClasses($array)
    {
        return $this->innerApiDocExtractor->clearClasses($array);
    }

    protected function generateHumanReadableTypes(array $array)
    {
        return $this->innerApiDocExtractor->generateHumanReadableTypes($array);
    }

    private function getShortName(string $className)
    {
        return $this->resourceIndex->has($className) ? $this->resourceIndex->getShortName($className) : null;
    }
}
