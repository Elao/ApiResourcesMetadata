<?php

/*
 * This file is part of the "elao/api-resources-metadata" package.
 *
 * Copyright (C) 2016 Elao
 *
 * @author Elao <contact@elao.com>
 */

namespace Elao\ApiResourcesMetadata\Bridge\Symfony\Bundle;

use Symfony\Component\HttpKernel\Bundle\Bundle as BaseBundle;

class Bundle extends BaseBundle
{
    public function __construct()
    {
        $this->getNamespace(); // To be removed once https://github.com/symfony/symfony/pull/20944 is released
        $this->name = 'ElaoApiResourcesMetadataBundle';
    }
}
