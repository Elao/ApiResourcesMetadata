<?php

/*
 * This file is part of the "elao/api-resources-metadata" package.
 *
 * Copyright (C) 2016 Elao
 *
 * @author Elao <contact@elao.com>
 */

namespace Elao\ApiResourcesMetadata\Tests\Unit\Resource\Locator;

use Elao\ApiResourcesMetadata\Resource\Locator\YamlFileLocator;

class YamlFileLocatorTest extends \PHPUnit_Framework_TestCase
{
    const RESOURCES_YML_PATH = __DIR__ . '/../../../Fixtures/res/resources.yml';
    const INVALID_RESOURCES_YML_PATH = __DIR__ . '/../../../Fixtures/res/invalid_resources.yml';

    public function testLocate()
    {
        $this->assertSame(
            [
                'Elao.Newsfeed' => 'Elao\ApiResourcesMetadata\Tests\Fixtures\Resource\NewsfeedResource',
                'Elao.Newsfeed.News' => 'Elao\ApiResourcesMetadata\Tests\Fixtures\Resource\NewsResource',
                'Elao.ArticleReference' => 'Elao\ApiResourcesMetadata\Tests\Fixtures\Resource\ArticleReferenceResource',
            ],
            (new YamlFileLocator(self::RESOURCES_YML_PATH))->locate()
        );
    }

    /**
     * @expectedException \Elao\ApiResourcesMetadata\Exception\InvalidArgumentException
     * @expectedExceptionMessage Missing class declaration for resource "Elao.Newsfeed.News".
     */
    public function testThrowsExceptionOnMissingResourceClass()
    {
        (new YamlFileLocator(self::INVALID_RESOURCES_YML_PATH))->locate();
    }
}
