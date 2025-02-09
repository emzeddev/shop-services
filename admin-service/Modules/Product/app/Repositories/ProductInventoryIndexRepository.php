<?php

namespace Modules\Product\Repositories;

use Modules\Core\Eloquent\Repository;

class ProductInventoryIndexRepository extends Repository
{
    /**
     * Specify model class name.
     */
    public function model(): string
    {
        return 'Modules\Product\Contracts\ProductInventoryIndex';
    }
}
