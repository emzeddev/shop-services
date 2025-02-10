<?php

namespace Modules\CatalogRule\Repositories;

use Modules\Core\Eloquent\Repository;

class CatalogRuleProductPriceRepository extends Repository
{
    /**
     * Specify Model class name.
     */
    public function model(): string
    {
        return 'Modules\CatalogRule\Contracts\CatalogRuleProductPrice';
    }
}
