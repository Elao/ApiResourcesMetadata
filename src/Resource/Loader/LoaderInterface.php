<?php

/*
 * This file is part of the "elao/api-resources-metadata" package.
 *
 * Copyright (C) 2016 Elao
 *
 * @author Elao <contact@elao.com>
 */

namespace Elao\ApiResourcesMetadata\Resource\Loader;

use Elao\ApiResourcesMetadata\Resource\ResourceMetadata;

interface LoaderInterface
{
    public function loadResourceMetadata(ResourceMetadata $resourceMetadata): bool;
}
