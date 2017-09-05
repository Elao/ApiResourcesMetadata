<?php

/*
 * This file is part of the "elao/api-resources-metadata" package.
 *
 * Copyright (C) 2016 Elao
 *
 * @author Elao <contact@elao.com>
 */

namespace Elao\ApiResourcesMetadata\Tests\Functional;

use Elao\ApiResourcesMetadata\Resource\Factory\ResourceMetadataFactory;
use Elao\ApiResourcesMetadata\Resource\Loader\PropertyInfoLoader;
use Elao\ApiResourcesMetadata\Resource\Loader\YamlFileLoader;
use Elao\ApiResourcesMetadata\Resource\Locator\YamlFileLocator;
use Elao\ApiResourcesMetadata\Resource\ResourceIndex;
use Elao\ApiResourcesMetadata\Resource\ResourceMetadata;
use Elao\ApiResourcesMetadata\Tests\Fixtures\Resource\NewsfeedResource;
use Elao\ApiResourcesMetadata\Tests\Fixtures\Resource\NewsResource;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;
use Symfony\Component\VarDumper\Test\VarDumperTestTrait;

class ResourceMetadataFactoryTest extends \PHPUnit_Framework_TestCase
{
    use VarDumperTestTrait;

    const RESOURCES_YML_PATH = __DIR__ . '/../Fixtures/res/resources.yml';

    /** @var ResourceMetadataFactory */
    private $factory;

    protected function setUp()
    {
        $phpDocExtractor = new PhpDocExtractor();
        $reflectionExtractor = new ReflectionExtractor();

        $listExtractors = [$reflectionExtractor];
        $typeExtractors = [$phpDocExtractor, $reflectionExtractor];
        $descriptionExtractors = [$phpDocExtractor];
        $accessExtractors = [$reflectionExtractor];

        $propertyInfo = new PropertyInfoExtractor(
            $listExtractors,
            $typeExtractors,
            $descriptionExtractors,
            $accessExtractors
        );

        $index = new ResourceIndex([new YamlFileLocator(self::RESOURCES_YML_PATH)]);

        $this->factory = new ResourceMetadataFactory($index, [
            new YamlFileLoader(self::RESOURCES_YML_PATH),
            new PropertyInfoLoader($propertyInfo),
        ]);
    }

    public function provideInvalidValues()
    {
        return [
            ['foo'],
            [Foo\Bar::class],
            [new \stdClass()],
        ];
    }

    /**
     * @dataProvider provideInvalidValues
     *
     * @expectedException \Elao\ApiResourcesMetadata\Exception\InvalidArgumentException
     */
    public function testThrowsExceptionOnInvalidValue($value)
    {
        $this->factory->getMetadataFor($value);
    }

    public function provideValidValues()
    {
        return [
            [NewsfeedResource::class],
            ['Elao.Newsfeed'],
            [new NewsfeedResource([], 'fr')],
        ];
    }

    /**
     * @dataProvider provideValidValues
     */
    public function testGetMetadataForValidValues($value)
    {
        $this->assertInstanceOf(ResourceMetadata::class, $this->factory->getMetadataFor($value));
    }

    public function provideGetMetadataForData()
    {
        return [
            [NewsResource::class, self::NEWS_RESOURCE_DUMP],
            [NewsfeedResource::class, self::NEWSFEED_RESOURCE_DUMP],
        ];
    }

    /**
     * @dataProvider provideGetMetadataForData
     */
    public function testGetMetadataFor($class, $dump)
    {
        $this->assertDumpEquals($dump, $this->factory->getMetadataFor($class));
    }

    const NEWSFEED_RESOURCE_DUMP = <<<'DUMP'
Elao\ApiResourcesMetadata\Resource\ResourceMetadata {
  +class: "Elao\ApiResourcesMetadata\Tests\Fixtures\Resource\NewsfeedResource"
  +shortName: "Elao.Newsfeed"
  +description: "A localized newsfeed gathering a collection of news"
  +attributes: array:2 [
    "news" => Elao\ApiResourcesMetadata\Attribute\ResourceAttributeMetadata {
      +name: "news"
      +description: "News for the localized feed"
      +required: true
      +type: "Elao\ApiResourcesMetadata\Tests\Fixtures\Resource\NewsResource[]"
      +originalType: "Elao\ApiResourcesMetadata\Tests\Fixtures\Resource\NewsResource[]"
    }
    "locale" => Elao\ApiResourcesMetadata\Attribute\ResourceAttributeMetadata {
      +name: "locale"
      +description: "The locale for the feed"
      +required: true
      +type: "string"
      +originalType: "string"
    }
  ]
}
DUMP;

    const NEWS_RESOURCE_DUMP = <<<'DUMP'
Elao\ApiResourcesMetadata\Resource\ResourceMetadata {
  +class: "Elao\ApiResourcesMetadata\Tests\Fixtures\Resource\NewsResource"
  +shortName: "Elao.Newsfeed.News"
  +description: "A single news from a newsfeed"
  +attributes: array:9 [
    "createdAt" => Elao\ApiResourcesMetadata\Attribute\ResourceAttributeMetadata {
      +name: "createdAt"
      +description: "The news creation date formatted to ISO 8601"
      +required: true
      +type: "string"
      +originalType: "\DateTimeImmutable"
    }
    "updatedAt" => Elao\ApiResourcesMetadata\Attribute\ResourceAttributeMetadata {
      +name: "updatedAt"
      +description: "The news modification date formatted to ISO 8601"
      +required: true
      +type: "string"
      +originalType: "\DateTime"
    }
    "deletedAt" => Elao\ApiResourcesMetadata\Attribute\ResourceAttributeMetadata {
      +name: "deletedAt"
      +description: "The news deletion date formatted to ISO 8601"
      +required: true
      +type: "string"
      +originalType: "\DateTimeInterface"
    }
    "image" => Elao\ApiResourcesMetadata\Attribute\ResourceAttributeMetadata {
      +name: "image"
      +description: null
      +required: false
      +type: "file"
      +originalType: "string"
    }
    "identifier" => Elao\ApiResourcesMetadata\Attribute\ResourceAttributeMetadata {
      +name: "identifier"
      +description: "Unique identifier"
      +required: true
      +type: "int"
      +originalType: "int"
    }
    "title" => Elao\ApiResourcesMetadata\Attribute\ResourceAttributeMetadata {
      +name: "title"
      +description: "The news title"
      +required: true
      +type: "string"
      +originalType: "string"
    }
    "content" => Elao\ApiResourcesMetadata\Attribute\ResourceAttributeMetadata {
      +name: "content"
      +description: "The news content"
      +required: true
      +type: "string"
      +originalType: "string"
    }
    "urgent" => Elao\ApiResourcesMetadata\Attribute\ResourceAttributeMetadata {
      +name: "urgent"
      +description: "Is this news urgent or not"
      +required: true
      +type: "bool"
      +originalType: "bool"
    }
    "references" => Elao\ApiResourcesMetadata\Attribute\ResourceAttributeMetadata {
      +name: "references"
      +description: "A list of related article or sources"
      +required: true
      +type: "Elao\ApiResourcesMetadata\Tests\Fixtures\Resource\ArticleReferenceResource[]"
      +originalType: "Elao\ApiResourcesMetadata\Tests\Fixtures\Resource\ArticleReferenceResource[]"
    }
  ]
}
DUMP;
}
