<?php

namespace Modules\CartRule\Repositories;

use Modules\Core\Eloquent\Repository;

class CartRuleCouponUsageRepository extends Repository
{
    /**
     * Specify Model class name
     */
    public function model(): string
    {
        return 'Modules\CartRule\Contracts\CartRuleCouponUsage';
    }
}
