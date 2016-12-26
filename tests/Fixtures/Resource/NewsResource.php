<?php

/*
 * This file is part of the "elao/api-resources-metadata" package.
 *
 * Copyright (C) 2016 Elao
 *
 * @author Elao <contact@elao.com>
 */

namespace Elao\ApiResourcesMetadata\Tests\Fixtures\Resource;

class NewsResource
{
    /**
     * Unique identifier
     *
     * @var int
     */
    private $identifier;

    /**
     * The news title
     *
     * Bar foo baz
     *
     * @var string
     */
    private $title;

    /**
     * The news content
     *
     * @var string
     */
    private $content;

    /** @var \DateTime */
    private $createdAt;

    /**
     * Is this news urgent or not
     *
     * @var bool
     */
    private $urgent;

    /**
     * A list of related article or sources
     *
     * @var ArticleReferenceResource[]
     */
    private $references;

    public function __construct(
        int $identifier,
        string $title,
        string $content,
        \DateTime $createdAt,
        bool $urgent = false,
        array $references = []
    ) {
        $this->identifier = $identifier;
        $this->title = $title;
        $this->content = $content;
        $this->createdAt = $createdAt;
        $this->urgent = $urgent;
        $this->references = $references;
    }

    public function getIdentifier(): int
    {
        return $this->identifier;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function isUrgent(): bool
    {
        return $this->urgent;
    }

    /**
     * @return ArticleReferenceResource[]
     */
    public function getReferences(): array
    {
        return $this->references;
    }
}
