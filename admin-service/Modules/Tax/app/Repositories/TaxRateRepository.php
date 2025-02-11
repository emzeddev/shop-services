<?php

namespace Modules\Tax\Repositories;

use Modules\Core\Eloquent\Repository;

class TaxRateRepository extends Repository
{
    /**
     * Specify model class name.
     */
    public function model(): string
    {
        return 'Modules\Tax\Contracts\TaxRate';
    }
}
