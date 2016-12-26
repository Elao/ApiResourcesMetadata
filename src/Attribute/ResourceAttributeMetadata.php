<?php

/*
 * This file is part of the "elao/api-resources-metadata" package.
 *
 * Copyright (C) 2016 Elao
 *
 * @author Elao <contact@elao.com>
 */

namespace Elao\ApiResourcesMetadata\Attribute;

final class ResourceAttributeMetadata
{
    /**
     * @var string
     *
     * @internal Public in order to reduce the size of the class' serialized representation.
     */
    public $name;

    /**
     * @var string
     *
     * @internal Public in order to reduce the size of the class' serialized representation.
     */
    public $description;

    /**
     * @var bool
     *
     * @internal Public in order to reduce the size of the class' serialized representation.
     */
    public $required = true;

    /**
     * @var string
     *
     * @internal Public in order to reduce the size of the class' serialized representation.
     */
    public $type;

    /**
     * @var string
     *
     * @internal Public in order to reduce the size of the class' serialized representation.
     */
    public $originalType;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription(string $description)
    {
        $this->description = $description;
    }

    public function isRequired(): bool
    {
        return $this->required;
    }

    public function setRequired(bool $required)
    {
        $this->required = $required;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType(string $type)
    {
        $this->type = $type;
    }

    public function getOriginalType()
    {
        return $this->originalType;
    }

    public function setOriginalType(string $originalType)
    {
        $this->originalType = $originalType;
    }

    /**
     * Returns the names of the properties that should be serialized.
     *
     * @return string[]
     */
    public function __sleep()
    {
        return [
            'name',
            'description',
            'required',
            'type',
            'originalType',
        ];
    }
}
