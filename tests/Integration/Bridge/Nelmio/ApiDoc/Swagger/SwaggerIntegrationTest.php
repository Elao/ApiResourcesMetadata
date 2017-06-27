<?php

/*
 * This file is part of the "elao/api-resources-metadata" package.
 *
 * Copyright (C) 2016 Elao
 *
 * @author Elao <contact@elao.com>
 */

namespace Elao\ApiResourcesMetadata\Tests\Integration\Bridge\Nelmio\ApiDoc\Swagger;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\ApplicationTester;

class SwaggerIntegrationTest extends KernelTestCase
{
    public function testSwaggerDump()
    {
        self::bootKernel();
        $application = new Application(self::$kernel);
        $application->setAutoExit(false);

        $applicationTester = new ApplicationTester($application);

        $applicationTester->run([
            'command' => 'api:swagger:dump',
            '--resource' => 'newsfeed',
        ]);

        $this->assertSame(0, $applicationTester->getStatusCode(), 'generates Swagger dump for newsfeed resource');

        $this->assertJsonStringEqualsJsonString(
            self::EXPECTED_NEWSFEED_SWAGGER_DUMP,
            $applicationTester->getDisplay(true)
        );
    }

    const EXPECTED_NEWSFEED_SWAGGER_DUMP = <<<'JSON'
{  
    "swaggerVersion":"1.2",
    "apiVersion":"0.1",
    "basePath":"\/api",
    "resourcePath":"\/newsfeed",
    "apis":[  
        {  
            "path":"\/newsfeed",
            "operations":[  
                {  
                    "method":"GET",
                    "summary":"Get the list of news",
                    "nickname":"get_newsfeed",
                    "parameters":[  

                    ],
                    "responseMessages":[  
                        {  
                            "code":200,
                            "message":"See standard HTTP status code reason for 200",
                            "responseModel":"Elao.Newsfeed"
                        }
                    ],
                    "type":"Elao.Newsfeed"
                }
            ]
        }
    ],
    "models":{  
        "Elao.ArticleReference":{  
            "id":"Elao.ArticleReference",
            "description":"object (Elao.ArticleReference)",
            "properties":{  
                "title":{  
                    "type":"string",
                    "description":"string"
                },
                "url":{  
                    "type":"string",
                    "description":"string"
                },
                "description":{  
                    "type":"string",
                    "description":"string"
                }
            },
            "required":[  
                "title",
                "url"
            ]
        },
        "Elao.Newsfeed.News":{  
            "id":"Elao.Newsfeed.News",
            "description":"A single news from a newsfeed",
            "properties":{  
                "createdAt":{  
                    "type":"string",
                    "description":"The news creation date formatted to ISO 8601",
                    "format":"date-time"
                },
                "identifier":{  
                    "type":"integer",
                    "description":"Unique identifier",
                    "format":"int32"
                },
                "title":{  
                    "type":"string",
                    "description":"The news title"
                },
                "content":{  
                    "type":"string",
                    "description":"The news content"
                },
                "image":{  
                    "type":"string",
                    "description":"file",
                    "format":"byte"
                },
                "urgent":{  
                    "type":"boolean",
                    "description":"Is this news urgent or not"
                },
                "references":{  
                    "type":"array",
                    "description":"A list of related article or sources",
                    "items":{  
                        "$ref":"Elao.ArticleReference"
                    }
                }
            },
            "required":[  
                "createdAt",
                "identifier",
                "title",
                "content",
                "urgent",
                "references"
            ]
        },
        "Elao.Newsfeed":{  
            "id":"Elao.Newsfeed",
            "description":"A localized newsfeed gathering a collection of news",
            "properties":{  
                "news":{  
                    "type":"array",
                    "description":"News for the localized feed",
                    "items":{  
                        "$ref":"Elao.Newsfeed.News"
                    }
                },
                "locale":{  
                    "type":"string",
                    "description":"The locale for the feed"
                }
            },
            "required":[  
                "news",
                "locale"
            ]
        }
    },
    "produces":[  

    ],
    "consumes":[  

    ],
    "authorizations":[  

    ]
}
JSON;
}
