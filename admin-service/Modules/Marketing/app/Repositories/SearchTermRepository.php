<?php

namespace Modules\Marketing\Repositories;

use Modules\Core\Eloquent\Repository;

class SearchTermRepository extends Repository
{
    /**
     * Specify model class name.
     */
    public function model(): string
    {
        return 'Modules\Marketing\Contracts\SearchTerm';
    }
}
