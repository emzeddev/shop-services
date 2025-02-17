<?php

namespace Modules\CartRule\Repositories;

use Modules\Core\Eloquent\Repository;

class CartRuleCustomerRepository extends Repository
{
    /**
     * Specify Model class name
     */
    public function model(): string
    {
        return 'Modules\CartRule\Contracts\CartRuleCustomer';
    }
}
