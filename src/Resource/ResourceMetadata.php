<?php

/*
 * This file is part of the "elao/api-resources-metadata" package.
 *
 * Copyright (C) 2016 Elao
 *
 * @author Elao <contact@elao.com>
 */

namespace Elao\ApiResourcesMetadata\Resource;

use Elao\ApiResourcesMetadata\Attribute\ResourceAttributeMetadata;

final class ResourceMetadata
{
    /**
     * @var string
     *
     * @internal Public in order to reduce the size of the class' serialized representation.
     */
    public $class;

    /**
     * @var string
     *
     * @internal Public in order to reduce the size of the class' serialized representation.
     */
    public $shortName;

    /**
     * @var string
     *
     * @internal Public in order to reduce the size of the class' serialized representation.
     */
    public $description;

    /**
     * @var ResourceAttributeMetadata[]
     *
     * @internal Public in order to reduce the size of the class' serialized representation.
     */
    public $attributes;

    public function __construct(string $class)
    {
        $this->class = $class;
        $this->shortName = '';
        $this->description = '';
        $this->attributes = [];
    }

    public function getClass(): string
    {
        return $this->class;
    }

    public function getShortName(): string
    {
        return $this->shortName;
    }

    public function setShortName(string $shortName)
    {
        $this->shortName = $shortName;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description)
    {
        $this->description = $description;
    }

    /**
     * @return ResourceAttributeMetadata[]
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    public function setAttributes(array $attributes)
    {
        $this->attributes = $attributes;
    }

    /**
     * @param $name
     *
     * @return ResourceAttributeMetadata|null
     */
    public function getAttribute($name)
    {
        return $this->attributes[$name] ?? null;
    }

    public function addAttribute(ResourceAttributeMetadata $attribute)
    {
        $this->attributes[$attribute->getName()] = $attribute;
    }

    /**
     * Returns the names of the properties that should be serialized.
     *
     * @return string[]
     */
    public function __sleep()
    {
        return [
            'class',
            'shortName',
            'description',
            'attributes',
        ];
    }
}
