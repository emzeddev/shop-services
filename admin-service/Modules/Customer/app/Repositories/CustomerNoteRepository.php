<?php

namespace Modules\Customer\Repositories;

use Modules\Core\Eloquent\Repository;

class CustomerNoteRepository extends Repository
{
    /**
     * Specify Model class name
     */
    public function model(): string
    {
        return 'Modules\Customer\Contracts\CustomerNote';
    }
}
