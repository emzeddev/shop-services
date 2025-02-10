<?php

namespace Modules\CatalogRule\Listeners;

use Modules\CatalogRule\Jobs\UpdateCreateProductIndex as UpdateCreateProductIndexJob;

class Product
{
    /**
     * @param  \Modules\Product\Contracts\Product  $product
     * @return void
     */
    public function afterUpdate($product)
    {
        UpdateCreateProductIndexJob::dispatch($product);
    }
}
