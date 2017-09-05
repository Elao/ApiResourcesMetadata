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

    /** @var \DateTimeImmutable */
    private $createdAt;

    /** @var \DateTime */
    private $updatedAt;

    /** @var \DateTimeInterface */
    private $deletedAt;

    /** @var string|null */
    private $image;

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
        \DateTimeImmutable $createdAt,
        \DateTime $updatedAt,
        \DateTimeInterface $deletedAt,
        string $image = null,
        bool $urgent = false,
        array $references = []
    ) {
        $this->identifier = $identifier;
        $this->title = $title;
        $this->content = $content;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->deletedAt = $deletedAt;
        $this->image = $image;
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

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    public function getDeletedAt(): \DateTimeInterface
    {
        return $this->deletedAt;
    }

    public function getImage(): string
    {
        return $this->image;
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
