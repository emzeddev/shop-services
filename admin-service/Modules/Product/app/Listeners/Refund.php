<?php

namespace Modules\Product\Listeners;

use Modules\Product\Jobs\UpdateCreateInventoryIndex as UpdateCreateInventoryIndexJob;

class Refund
{
    /**
     * After refund is created
     *
     * @param  \Modules\Sale\Contracts\Refund  $refund
     * @return void
     */
    public function afterCreate($refund)
    {
        $productIds = $refund->items
            ->pluck('product_id')
            ->toArray();

        UpdateCreateInventoryIndexJob::dispatch($productIds);
    }
}
