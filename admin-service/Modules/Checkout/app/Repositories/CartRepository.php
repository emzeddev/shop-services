<?php

namespace Modules\Checkout\Repositories;

use Modules\Core\Eloquent\Repository;

class CartRepository extends Repository
{
    /**
     * Specify Model class name
     */
    public function model(): string
    {
        return 'Modules\Checkout\Contracts\Cart';
    }
}
