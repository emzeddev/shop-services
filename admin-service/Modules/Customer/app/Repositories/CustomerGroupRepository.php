<?php

namespace Modules\Customer\Repositories;

use Modules\Core\Eloquent\Repository;

class CustomerGroupRepository extends Repository
{
    /**
     * Specify model class name.
     */
    public function model(): string
    {
        return 'Modules\Customer\Contracts\CustomerGroup';
    }
}
