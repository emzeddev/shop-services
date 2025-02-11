<?php

namespace Modules\Marketing\Repositories;

use Modules\Core\Eloquent\Repository;

class EventRepository extends Repository
{
    /**
     * Specify model class name.
     */
    public function model(): string
    {
        return 'Modules\Marketing\Contracts\Event';
    }
}
