<?php

/*
 * This file is part of the "elao/api-resources-metadata" package.
 *
 * Copyright (C) 2016 Elao
 *
 * @author Elao <contact@elao.com>
 */

namespace Elao\ApiResourcesMetadata\Tests\Fixtures\Resource;

class ArticleReferenceResource
{
    /** @var string */
    private $title;

    /** @var string */
    private $url;

    /** @var string|null */
    private $description;

    public function __construct(string $title, string $url, string $description = null)
    {
        $this->title = $title;
        $this->url = $url;
        $this->description = $description;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getDescription()
    {
        return $this->description;
    }
}
