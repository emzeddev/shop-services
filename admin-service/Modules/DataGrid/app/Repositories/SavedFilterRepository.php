<?php

namespace Modules\DataGrid\Repositories;

use Modules\Core\Eloquent\Repository;
use Modules\DataGrid\Contracts\SavedFilter;

class SavedFilterRepository extends Repository
{
    /**
     * Specify model class name.
     */
    public function model(): string
    {
        return SavedFilter::class;
    }
}
