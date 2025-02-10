<?php

namespace Modules\Sales\Repositories;

use Modules\Core\Eloquent\Repository;
use Modules\Sales\Contracts\OrderTransaction;

class OrderTransactionRepository extends Repository
{
    /**
     * Specify model class name.
     */
    public function model(): string
    {
        return OrderTransaction::class;
    }
}
