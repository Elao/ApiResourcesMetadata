<?php

/*
 * This file is part of the "elao/api-resources-metadata" package.
 *
 * Copyright (C) 2016 Elao
 *
 * @author Elao <contact@elao.com>
 */

namespace Elao\ApiResourcesMetadata\Tests\Functional\Bridge\Nelmio\ApiDoc;

use Elao\ApiResourcesMetadata\Bridge\Nelmio\ApiDoc\Parser\ApiResourceMetadataParser;
use Elao\ApiResourcesMetadata\Resource\Factory\ResourceMetadataFactory;
use Elao\ApiResourcesMetadata\Resource\Loader\PropertyInfoLoader;
use Elao\ApiResourcesMetadata\Resource\Loader\YamlFileLoader;
use Elao\ApiResourcesMetadata\Resource\Locator\YamlFileLocator;
use Elao\ApiResourcesMetadata\Resource\ResourceIndex;
use Elao\ApiResourcesMetadata\Tests\Fixtures\Resource\NewsfeedResource;
use Elao\ApiResourcesMetadata\Tests\Fixtures\Resource\NewsResource;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;
use Symfony\Component\VarDumper\Test\VarDumperTestTrait;

class ApiResourceMetadataParserTest extends \PHPUnit_Framework_TestCase
{
    use VarDumperTestTrait;

    const RESOURCES_YML_PATH = __DIR__ . '/../../../../Fixtures/res/resources.yml';

    /** @var ApiResourceMetadataParser */
    private $parser;

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

        $factory = new ResourceMetadataFactory($index, [
            new YamlFileLoader(self::RESOURCES_YML_PATH),
            new PropertyInfoLoader($propertyInfo),
        ]);

        $this->parser = new ApiResourceMetadataParser($index, $factory);
    }

    public function provideTestParseData()
    {
        return [
            [['class' => NewsResource::class], self::NEWS_RESOURCE_DUMP],
            [['class' => NewsfeedResource::class], self::NEWSFEED_RESOURCE_DUMP],
        ];
    }

    /**
     * @dataProvider provideTestParseData
     */
    public function testParse(array $toParse, $expected)
    {
        $this->assertDumpEquals($expected, $this->parser->parse($toParse));
    }

    const NEWS_RESOURCE_DUMP = <<<'DUMP'
array:9 [
  "createdAt" => array:5 [
    "dataType" => "string"
    "actualType" => "datetime"
    "required" => true
    "description" => "The news creation date formatted to ISO 8601"
    "readonly" => null
  ]
  "updatedAt" => array:5 [
    "dataType" => "string"
    "actualType" => "datetime"
    "required" => true
    "description" => "The news modification date formatted to ISO 8601"
    "readonly" => null
  ]
  "deletedAt" => array:5 [
    "dataType" => "string"
    "actualType" => "datetime"
    "required" => true
    "description" => "The news deletion date formatted to ISO 8601"
    "readonly" => null
  ]
  "image" => array:5 [
    "dataType" => "file"
    "actualType" => "file"
    "required" => false
    "description" => ""
    "readonly" => null
  ]
  "identifier" => array:5 [
    "dataType" => "int"
    "actualType" => "integer"
    "required" => true
    "description" => "Unique identifier"
    "readonly" => null
  ]
  "title" => array:5 [
    "dataType" => "string"
    "actualType" => "string"
    "required" => true
    "description" => "The news title"
    "readonly" => null
  ]
  "content" => array:5 [
    "dataType" => "string"
    "actualType" => "string"
    "required" => true
    "description" => "The news content"
    "readonly" => null
  ]
  "urgent" => array:5 [
    "dataType" => "bool"
    "actualType" => "boolean"
    "required" => true
    "description" => "Is this news urgent or not"
    "readonly" => null
  ]
  "references" => array:7 [
    "dataType" => null
    "actualType" => "collection"
    "required" => true
    "description" => "A list of related article or sources"
    "readonly" => null
    "subType" => "Elao.ArticleReference"
    "children" => array:3 [
      "title" => array:5 [
        "dataType" => "string"
        "actualType" => "string"
        "required" => true
        "description" => ""
        "readonly" => null
      ]
      "url" => array:5 [
        "dataType" => "string"
        "actualType" => "string"
        "required" => true
        "description" => ""
        "readonly" => null
      ]
      "description" => array:5 [
        "dataType" => "string"
        "actualType" => "string"
        "required" => false
        "description" => ""
        "readonly" => null
      ]
    ]
  ]
]
DUMP;

    const NEWSFEED_RESOURCE_DUMP = <<<'DUMP'
array:2 [
  "news" => array:7 [
    "dataType" => null
    "actualType" => "collection"
    "required" => true
    "description" => "News for the localized feed"
    "readonly" => null
    "subType" => "Elao.Newsfeed.News"
    "children" => array:9 [
      "createdAt" => array:5 [
        "dataType" => "string"
        "actualType" => "datetime"
        "required" => true
        "description" => "The news creation date formatted to ISO 8601"
        "readonly" => null
      ]
      "updatedAt" => array:5 [
        "dataType" => "string"
        "actualType" => "datetime"
        "required" => true
        "description" => "The news modification date formatted to ISO 8601"
        "readonly" => null
      ]
      "deletedAt" => array:5 [
        "dataType" => "string"
        "actualType" => "datetime"
        "required" => true
        "description" => "The news deletion date formatted to ISO 8601"
        "readonly" => null
      ]
      "image" => array:5 [
        "dataType" => "file"
        "actualType" => "file"
        "required" => false
        "description" => ""
        "readonly" => null
      ]
      "identifier" => array:5 [
        "dataType" => "int"
        "actualType" => "integer"
        "required" => true
        "description" => "Unique identifier"
        "readonly" => null
      ]
      "title" => array:5 [
        "dataType" => "string"
        "actualType" => "string"
        "required" => true
        "description" => "The news title"
        "readonly" => null
      ]
      "content" => array:5 [
        "dataType" => "string"
        "actualType" => "string"
        "required" => true
        "description" => "The news content"
        "readonly" => null
      ]
      "urgent" => array:5 [
        "dataType" => "bool"
        "actualType" => "boolean"
        "required" => true
        "description" => "Is this news urgent or not"
        "readonly" => null
      ]
      "references" => array:7 [
        "dataType" => null
        "actualType" => "collection"
        "required" => true
        "description" => "A list of related article or sources"
        "readonly" => null
        "subType" => "Elao.ArticleReference"
        "children" => array:3 [
          "title" => array:5 [
            "dataType" => "string"
            "actualType" => "string"
            "required" => true
            "description" => ""
            "readonly" => null
          ]
          "url" => array:5 [
            "dataType" => "string"
            "actualType" => "string"
            "required" => true
            "description" => ""
            "readonly" => null
          ]
          "description" => array:5 [
            "dataType" => "string"
            "actualType" => "string"
            "required" => false
            "description" => ""
            "readonly" => null
          ]
        ]
      ]
    ]
  ]
  "locale" => array:5 [
    "dataType" => "string"
    "actualType" => "string"
    "required" => true
    "description" => "The locale for the feed"
    "readonly" => null
  ]
]
DUMP;
}
