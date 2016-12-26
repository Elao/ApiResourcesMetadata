<?php

/*
 * This file is part of the "elao/api-resources-metadata" package.
 *
 * Copyright (C) 2016 Elao
 *
 * @author Elao <contact@elao.com>
 */

namespace Elao\ApiResourcesMetadata\Tests\Fixtures\Resource;

/**
 * A localized newsfeed gathering a collection of news
 */
class NewsfeedResource
{
    /**
     * News for the localized feed
     *
     * @var NewsResource[]
     */
    private $news;

    /**
     * The locale for the feed
     *
     * @var string
     */
    private $locale;

    public function __construct(array $news, string $locale)
    {
        $this->news = $news;
        $this->locale = $locale;
    }

    /**
     * @return NewsResource[]
     */
    public function getNews(): array
    {
        return $this->news;
    }

    public function getLocale(): string
    {
        return $this->locale;
    }
}
