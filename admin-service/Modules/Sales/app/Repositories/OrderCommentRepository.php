<?php

namespace Modules\Sales\Repositories;

use Modules\Core\Eloquent\Repository;

class OrderCommentRepository extends Repository
{
    /**
     * Specify Model class name
     */
    public function model(): string
    {
        return 'Modules\Sales\Contracts\OrderComment';
    }
}
