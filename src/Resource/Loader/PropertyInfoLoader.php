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
use phpDocumentor\Reflection\DocBlockFactory;
use phpDocumentor\Reflection\DocBlockFactoryInterface;
use phpDocumentor\Reflection\Types\ContextFactory;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;
use Symfony\Component\PropertyInfo\Type;

class PropertyInfoLoader implements LoaderInterface
{
    /** @var PropertyInfoExtractor */
    private $extractor;

    /** @var DocBlockFactoryInterface */
    private $docBlockFactory;

    /** @var ContextFactory */
    private $contextFactory;

    public function __construct(PropertyInfoExtractor $extractor, DocBlockFactoryInterface $docBlockFactory = null)
    {
        $this->extractor = $extractor;
        $this->docBlockFactory = $docBlockFactory ?: DocBlockFactory::createInstance();
        $this->contextFactory = new ContextFactory();
    }

    public function loadResourceMetadata(ResourceMetadata $resourceMetadata): bool
    {
        $class = $resourceMetadata->getClass();

        if (!class_exists($class)) {
            return false;
        }

        $resourceMetadata->getDescription() ?: $resourceMetadata->setDescription($this->getClassDocBlock($class));

        foreach ($this->extractor->getProperties($class) as $name) {
            $attributeMetadata = $resourceMetadata->getAttribute($name) ?? new ResourceAttributeMetadata($name);

            if (!empty($description = $this->extractor->getLongDescription($class, $name))) {
                $attributeMetadata->getDescription() ?: $attributeMetadata->setDescription($description);
            }

            /** @var Type[] $types */
            $types = $this->extractor->getTypes($class, $name);

            if (isset($types[0])) {
                $type = $types[0];
                $attributeMetadata->setRequired(!$type->isNullable());

                $phpType = $types[0]->getBuiltinType();
                if ($type->isCollection()) {
                    $collectionValueType = $type->getCollectionValueType();

                    if ($collectionValueType) {
                        if (Type::BUILTIN_TYPE_OBJECT === $collectionValueType->getBuiltinType()) {
                            $phpType = $collectionValueType->getClassName();
                        } else {
                            $phpType = $collectionValueType->getBuiltinType();
                        }

                        $phpType .= '[]';
                    }
                }

                if (Type::BUILTIN_TYPE_OBJECT === $type->getBuiltinType()) {
                    $phpType = $type->getClassName();
                }

                $attributeMetadata->getOriginalType() ?: $attributeMetadata->setOriginalType($phpType);
                $attributeMetadata->getType() ?: $attributeMetadata->setType($phpType);
            }

            if ($description = $this->extractor->getShortDescription($class, $name)) {
                $attributeMetadata->setDescription($description);
            }

            $resourceMetadata->addAttribute($attributeMetadata);
        }

        return true;
    }

    /**
     * @param string $class
     *
     * @return string
     */
    private function getClassDocBlock(string $class)
    {
        $reflectionProperty = new \ReflectionClass($class);

        try {
            return (string) $this->docBlockFactory->create(
                $reflectionProperty,
                $this->contextFactory->createFromReflector($reflectionProperty)
            )->getSummary();
        } catch (\InvalidArgumentException $e) {
            return '';
        }
    }
}
