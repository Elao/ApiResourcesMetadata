<?php

/*
 * This file is part of the "elao/api-resources-metadata" package.
 *
 * Copyright (C) 2016 Elao
 *
 * @author Elao <contact@elao.com>
 */

namespace Elao\ApiResourcesMetadata\Bridge\Nelmio\ApiDoc\Parser;

use Elao\ApiResourcesMetadata\Attribute\ResourceAttributeMetadata;
use Elao\ApiResourcesMetadata\Resource\Factory\ResourceMetadataFactory;
use Elao\ApiResourcesMetadata\Resource\ResourceIndex;
use Nelmio\ApiDocBundle\DataTypes;
use Nelmio\ApiDocBundle\Parser\ParserInterface;

class ApiResourceMetadataParser implements ParserInterface
{
    /** @var ResourceMetadataFactory */
    private $metadataFactory;

    /** @var ResourceIndex */
    private $resourceIndex;

    public function __construct(ResourceIndex $resourceIndex, ResourceMetadataFactory $metadataFactory)
    {
        $this->metadataFactory = $metadataFactory;
        $this->resourceIndex = $resourceIndex;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(array $item)
    {
        return $this->resourceIndex->has($item['class']);
    }

    /**
     * {@inheritdoc}
     */
    public function parse(array $item)
    {
        $class = $item['class'];

        return $this->doParse($class);
    }

    private function doParse($class)
    {
        $data = [];

        $resourceMetadata = $this->metadataFactory->getMetadataFor($class);

        foreach ($resourceMetadata->getAttributes() as $attributeMetadata) {
            list($actualType, $subType) = $this->resolveActualType($attributeMetadata);

            $attributeData = [
                'dataType' => $attributeMetadata->getType(),
                'actualType' => $actualType,
                'required' => $attributeMetadata->isRequired(),
                'description' => $attributeMetadata->getDescription() ?: '',
                'readonly' => null,
            ];

            if ($subType) {
                $attributeData['subType'] = $subType;
                $attributeData['dataType'] = null; // ApiDocExtractor will generate it

                if ($this->supports(['class' => $subType])) {
                    $attributeData['subType'] = $this->resourceIndex->getShortName($subType);
                    $attributeData['children'] = $this->doParse($subType);
                }
            }

            $data[$attributeMetadata->getName()] = $attributeData;
        }

        return $data;
    }

    private function resolveActualType(ResourceAttributeMetadata $attributeMetadata)
    {
        if (\DateTime::class === ltrim($attributeMetadata->getOriginalType(), '\\')) {
            return [DataTypes::DATETIME, null];
        }

        $type = $attributeMetadata->getType();

        $actualType = null;
        $subType = null;

        // If it's a collection of models
        if (false !== strpos($type, '[]')) {
            $actualType = DataTypes::COLLECTION;
        } elseif (class_exists($type)) {
            $actualType = DataTypes::MODEL;
        }

        $type = str_replace('[]', '', $type, $count);

        switch ($type) {
            case 'bool':
            case 'boolean':
                $type = DataTypes::BOOLEAN;
                break;
            case 'int':
            case 'integer':
                $type = DataTypes::INTEGER;
                break;
            case 'double':
            case 'float':
                $type = DataTypes::FLOAT;
                break;
            case 'file':
                $type = DataTypes::FILE;
                break;
            case 'string':
                $type = DataTypes::STRING;
                break;
        }

        if (in_array($actualType, [DataTypes::COLLECTION, DataTypes::MODEL])) {
            $subType = $type;
        } else {
            $actualType = $type;
        }

        return [$actualType, $subType];
    }
}
