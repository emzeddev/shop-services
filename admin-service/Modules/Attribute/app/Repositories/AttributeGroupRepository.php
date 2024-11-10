<?php

namespace Modules\Attribute\Repositories;

use Modules\Core\Eloquent\Repository;

class AttributeGroupRepository extends Repository
{
    /**
     * Specify Model class name
    */
    public function model(): string
    {
        return 'Modules\Attribute\Contracts\AttributeGroup';
    }
}
