<?php

namespace Modules\Core\Repositories;

use Modules\Core\Eloquent\Repository;

class CountryRepository extends Repository
{
    /**
     * Specify Model class name
     */
    public function model(): string
    {
        return 'Modules\Core\Contracts\Country';
    }
}
