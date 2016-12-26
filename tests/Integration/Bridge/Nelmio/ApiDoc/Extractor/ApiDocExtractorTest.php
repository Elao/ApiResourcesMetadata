<?php

/*
 * This file is part of the "elao/api-resources-metadata" package.
 *
 * Copyright (C) 2016 Elao
 *
 * @author Elao <contact@elao.com>
 */

namespace Elao\ApiResourcesMetadata\Tests\Integration\Bridge\Nelmio\ApiDoc\Extractor;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\VarDumper\Test\VarDumperTestTrait;

class ApiDocExtractorTest extends KernelTestCase
{
    use VarDumperTestTrait;

    public function testExtractedAnnotations()
    {
        self::bootKernel();
        $container = self::$kernel->getContainer();

        $this->assertDumpEquals(
            self::EXPECTED_DUMP,
            $container->get('nelmio_api_doc.extractor.api_doc_extractor')->all()
        );
    }

    const EXPECTED_DUMP = <<<'DUMP'
array:1 [
  0 => array:2 [
    "annotation" => Nelmio\ApiDocBundle\Annotation\ApiDoc {
      -requirements: []
      -views: []
      -filters: []
      -parameters: []
      -headers: []
      -input: null
      -output: "Elao\ApiResourcesMetadata\Tests\Fixtures\Resource\NewsfeedResource"
      -link: null
      -description: "Get the list of news"
      -section: "Newsfeed"
      -documentation: ""
      -resource: true
      -method: "GET"
      -host: ""
      -uri: "/api/newsfeed"
      -response: array:2 [
        "news" => array:7 [
          "dataType" => "array of objects (NewsResource)"
          "actualType" => "collection"
          "required" => true
          "description" => "News for the localized feed"
          "readonly" => null
          "subType" => "Elao\ApiResourcesMetadata\Tests\Fixtures\Resource\NewsResource"
          "children" => array:6 [
            "createdAt" => array:5 [
              "dataType" => "string"
              "actualType" => "datetime"
              "required" => true
              "description" => "The news creation date formatted to ISO 8601"
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
              "dataType" => "array of objects (ArticleReferenceResource)"
              "actualType" => "collection"
              "required" => true
              "description" => "A list of related article or sources"
              "readonly" => null
              "subType" => "Elao\ApiResourcesMetadata\Tests\Fixtures\Resource\ArticleReferenceResource"
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
      -route: Symfony\Component\Routing\Route {
        -path: "/api/newsfeed"
        -host: ""
        -schemes: []
        -methods: array:1 [
          0 => "GET"
        ]
        -defaults: array:1 [
          "_controller" => "Elao\ApiResourcesMetadata\Tests\Fixtures\Integration\Symfony\TestBundle\Controller\NewsfeedResourceController::getAction"
        ]
        -requirements: []
        -options: array:1 [
          "compiler_class" => "Symfony\Component\Routing\RouteCompiler"
        ]
        -compiled: Symfony\Component\Routing\CompiledRoute {
          -variables: []
          -tokens: array:1 [
            0 => array:2 [
              0 => "text"
              1 => "/api/newsfeed"
            ]
          ]
          -staticPrefix: "/api/newsfeed"
          -regex: "#^/api/newsfeed$#s"
          -pathVariables: []
          -hostVariables: []
          -hostRegex: null
          -hostTokens: []
        }
        -condition: ""
      }
      -https: false
      -authentication: false
      -authenticationRoles: []
      -cache: null
      -deprecated: false
      -statusCodes: []
      -resourceDescription: "News"
      -responseMap: array:1 [
        200 => "Elao\ApiResourcesMetadata\Tests\Fixtures\Resource\NewsfeedResource"
      ]
      -parsedResponseMap: array:1 [
        200 => array:2 [
          "type" => array:3 [
            "class" => "Elao\ApiResourcesMetadata\Tests\Fixtures\Resource\NewsfeedResource"
            "groups" => []
            "options" => []
          ]
          "model" => array:2 [
            "news" => array:7 [
              "dataType" => "array of objects (NewsResource)"
              "actualType" => "collection"
              "required" => true
              "description" => "News for the localized feed"
              "readonly" => null
              "subType" => "Elao\ApiResourcesMetadata\Tests\Fixtures\Resource\NewsResource"
              "children" => array:6 [
                "createdAt" => array:5 [
                  "dataType" => "string"
                  "actualType" => "datetime"
                  "required" => true
                  "description" => "The news creation date formatted to ISO 8601"
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
                  "dataType" => "array of objects (ArticleReferenceResource)"
                  "actualType" => "collection"
                  "required" => true
                  "description" => "A list of related article or sources"
                  "readonly" => null
                  "subType" => "Elao\ApiResourcesMetadata\Tests\Fixtures\Resource\ArticleReferenceResource"
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
        ]
      ]
      -tags: []
    }
    "resource" => "/api/newsfeed"
  ]
]
DUMP;
}
