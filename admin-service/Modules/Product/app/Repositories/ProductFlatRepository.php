<?php

namespace Modules\Product\Repositories;

use Modules\Core\Eloquent\Repository;

class ProductFlatRepository extends Repository
{
    /**
     * Specify model.
     */
    public function model(): string
    {
        return 'Modules\Product\Contracts\ProductFlat';
    }
}
