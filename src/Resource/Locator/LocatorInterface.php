<?php

/*
 * This file is part of the "elao/api-resources-metadata" package.
 *
 * Copyright (C) 2016 Elao
 *
 * @author Elao <contact@elao.com>
 */

namespace Elao\ApiResourcesMetadata\Resource\Locator;

interface LocatorInterface
{
    /**
     * @return array Located resources indexed by short name
     */
    public function locate(): array;
}
