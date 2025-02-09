<?php

namespace Modules\Product\Repositories;

class ProductVideoRepository extends ProductMediaRepository
{
    /**
     * Specify model class name.
     */
    public function model(): string
    {
        return 'Modules\Product\Contracts\ProductVideo';
    }
}
