<?php

namespace Modules\Product\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event handler mappings for the application.
     *
     * @var array<string, array<int, string>>
     */
    /**
     * The event handler mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'catalog.product.create.after'  => [
            'Modules\Product\Listeners\Product@afterCreate',
        ],
        'catalog.product.update.after'  => [
            'Modules\Product\Listeners\Product@afterUpdate',
        ],
        'catalog.product.delete.before' => [
            'Modules\Product\Listeners\Product@beforeDelete',
        ],
        'checkout.order.save.after'     => [
            'Modules\Product\Listeners\Order@afterCancelOrCreate',
        ],
        'sales.order.cancel.after'      => [
            'Modules\Product\Listeners\Order@afterCancelOrCreate',
        ],
        'sales.refund.save.after'       => [
            'Modules\Product\Listeners\Refund@afterCreate',
        ],
    ];

    /**
     * Indicates if events should be discovered.
     *
     * @var bool
     */
    protected static $shouldDiscoverEvents = true;

    /**
     * Configure the proper event listeners for email verification.
     */
    protected function configureEmailVerification(): void
    {
        //
    }
}
