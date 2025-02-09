<?php

namespace Modules\Product\Repositories;

class ProductImageRepository extends ProductMediaRepository
{
    /**
     * Specify model class name.
     */
    public function model(): string
    {
        return 'Modules\Product\Contracts\ProductImage';
    }
}
