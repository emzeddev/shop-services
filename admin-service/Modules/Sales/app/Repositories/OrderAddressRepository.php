<?php

namespace Modules\Sales\Repositories;

use Modules\Core\Eloquent\Repository;

/**
 * Order Address Repository
 *
 * @author    Jitendra Singh <jitendra@Modules.com>
 * @copyright 2018 Modules Software Pvt Ltd (http://www.Modules.com)
 */
class OrderAddressRepository extends Repository
{
    /**
     * Specify Model class name
     */
    public function model(): string
    {
        return 'Modules\Sales\Contracts\OrderAddress';
    }
}
