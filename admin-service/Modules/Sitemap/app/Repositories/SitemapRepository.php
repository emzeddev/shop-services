<?php

namespace Modules\Sitemap\Repositories;

use Modules\Core\Eloquent\Repository;
use Modules\Sitemap\Contracts\Sitemap;

class SitemapRepository extends Repository
{
    /**
     * Specify model class name.
     */
    public function model(): string
    {
        return Sitemap::class;
    }
}
