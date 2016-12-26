<?php

/*
 * This file is part of the "elao/api-resources-metadata" package.
 *
 * Copyright (C) 2016 Elao
 *
 * @author Elao <contact@elao.com>
 */

namespace Elao\ApiResourcesMetadata\Tests\Fixtures\Integration\Symfony\TestBundle\Controller;

use Nelmio\ApiDocBundle\Annotation as Nelmio;

class NewsfeedResourceController
{
    /**
     * @Nelmio\ApiDoc(
     *  resource=true,
     *  resourceDescription="News",
     *  section="Newsfeed",
     *  description="Get the list of news",
     *  output="Elao\ApiResourcesMetadata\Tests\Fixtures\Resource\NewsfeedResource",
     * )
     */
    public function getAction()
    {
        // noop
    }
}
