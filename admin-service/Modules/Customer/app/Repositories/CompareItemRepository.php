<?php

namespace Modules\Customer\Repositories;

use Modules\Core\Eloquent\Repository;
use Modules\Customer\Contracts\CompareItem;

class CompareItemRepository extends Repository
{
    /**
     * Specify Model class name
     */
    public function model(): string
    {
        return CompareItem::class;
    }
}
