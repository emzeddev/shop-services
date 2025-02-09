<?php

namespace Modules\Core\Repositories;

use Modules\Core\Eloquent\Repository;

class ExchangeRateRepository extends Repository
{
    /**
     * Specify model class name.
     */
    public function model(): string
    {
        return 'Modules\Core\Contracts\CurrencyExchangeRate';
    }
}
