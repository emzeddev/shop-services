<?php

namespace Modules\Product\Listeners;

use Modules\Product\Jobs\UpdateCreateInventoryIndex as UpdateCreateInventoryIndexJob;

class Order
{
    /**
     * After order is created
     *
     * @param  \Modules\Sale\Contracts\Order  $order
     * @return void
     */
    public function afterCancelOrCreate($order)
    {
        $productIds = $order->all_items
            ->pluck('product_id')
            ->toArray();

        UpdateCreateInventoryIndexJob::dispatch($productIds);
    }
}
