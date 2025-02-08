<?php

namespace Modules\Inventory\Repositories;

use Modules\Core\Eloquent\Repository;

class InventorySourceRepository extends Repository
{
    /**
     * Specify model class name.
     */
    public function model(): string
    {
        return 'Modules\Inventory\Contracts\InventorySource';
    }
}
