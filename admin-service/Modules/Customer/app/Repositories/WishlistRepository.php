<?php

namespace Modules\Customer\Repositories;

use Modules\Core\Eloquent\Repository;
use Modules\Customer\Contracts\Wishlist;

class WishlistRepository extends Repository
{
    /**
     * Specify model class name.
     */
    public function model(): string
    {
        return Wishlist::class;
    }
}
