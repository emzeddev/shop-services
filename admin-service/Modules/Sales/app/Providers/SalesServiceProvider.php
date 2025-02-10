<?php

namespace Modules\Sales\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Nwidart\Modules\Traits\PathNamespace;

use Modules\Sales\Contracts\DownloadableLinkPurchased as DownloadableLinkPurchasedContract;
use Modules\Sales\Models\DownloadableLinkPurchased;

use Modules\Sales\Contracts\Invoice as InvoiceContract;
use Modules\Sales\Models\Invoice;

use Modules\Sales\Contracts\InvoiceItem as InvoiceItemContract;
use Modules\Sales\Models\InvoiceItem;

use Modules\Sales\Contracts\Order as OrderContract;
use Modules\Sales\Models\Order;

use Modules\Sales\Contracts\OrderAddress as OrderAddressContract;
use Modules\Sales\Models\OrderAddress;

use Modules\Sales\Contracts\OrderComment as OrderCommentContract;
use Modules\Sales\Models\OrderComment;

use Modules\Sales\Contracts\OrderItem as OrderItemContract;
use Modules\Sales\Models\OrderItem;

use Modules\Sales\Contracts\OrderPayment as OrderPaymentContract;
use Modules\Sales\Models\OrderPayment;

use Modules\Sales\Contracts\OrderTransaction as OrderTransactionContract;
use Modules\Sales\Models\OrderTransaction;

use Modules\Sales\Contracts\Refund as RefundContract;
use Modules\Sales\Models\Refund;

use Modules\Sales\Contracts\RefundItem as RefundItemContract;
use Modules\Sales\Models\RefundItem;


use Modules\Sales\Contracts\Shipment as ShipmentContract;
use Modules\Sales\Models\Shipment;

use Modules\Sales\Contracts\ShipmentItem as ShipmentItemContract;
use Modules\Sales\Models\ShipmentItem;

class SalesServiceProvider extends ServiceProvider
{
    use PathNamespace;

    protected string $name = 'Sales';

    protected string $nameLower = 'sales';

    /**
     * Boot the application events.
     */
    public function boot(): void
    {
        $this->registerCommands();
        $this->registerCommandSchedules();
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrationsFrom(module_path($this->name, 'database/migrations'));
        $this->registerModelProxies();
    }

    /**
     * Register the service provider.
     */
    public function register(): void
    {
        $this->app->register(EventServiceProvider::class);
        $this->app->register(RouteServiceProvider::class);
    }

    /**
     * Register commands in the format of Command::class
     */
    protected function registerCommands(): void
    {
        // $this->commands([]);
    }

    /**
     * Register command Schedules.
     */
    protected function registerCommandSchedules(): void
    {
        // $this->app->booted(function () {
        //     $schedule = $this->app->make(Schedule::class);
        //     $schedule->command('inspire')->hourly();
        // });
    }

    /**
     * Register translations.
     */
    public function registerTranslations(): void
    {
        $langPath = resource_path('lang/modules/'.$this->nameLower);

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, $this->nameLower);
            $this->loadJsonTranslationsFrom($langPath);
        } else {
            $this->loadTranslationsFrom(module_path($this->name, 'resources/lang'), $this->nameLower);
            $this->loadJsonTranslationsFrom(module_path($this->name, 'resources/lang'));
        }
    }

    /**
     * Register config.
     */
    protected function registerConfig(): void
    {
        $this->publishes([module_path($this->name, 'config/config.php') => config_path($this->nameLower.'.php')], 'config');
        $this->mergeConfigFrom(module_path($this->name, 'config/config.php'), $this->nameLower);
    }

    /**
     * Register views.
     */
    public function registerViews(): void
    {
        $viewPath = resource_path('views/modules/'.$this->nameLower);
        $sourcePath = module_path($this->name, 'resources/views');

        $this->publishes([$sourcePath => $viewPath], ['views', $this->nameLower.'-module-views']);

        $this->loadViewsFrom(array_merge($this->getPublishableViewPaths(), [$sourcePath]), $this->nameLower);

        $componentNamespace = $this->module_namespace($this->name, $this->app_path(config('modules.paths.generator.component-class.path')));
        Blade::componentNamespace($componentNamespace, $this->nameLower);
    }

    /**
     * Get the services provided by the provider.
     */
    public function provides(): array
    {
        return [];
    }

    private function getPublishableViewPaths(): array
    {
        $paths = [];
        foreach (config('view.paths') as $path) {
            if (is_dir($path.'/modules/'.$this->nameLower)) {
                $paths[] = $path.'/modules/'.$this->nameLower;
            }
        }

        return $paths;
    }



    public function registerModelProxies() {
        $this->app->concord->registerModel(
            DownloadableLinkPurchasedContract::class,
            DownloadableLinkPurchased::class
        );

        $this->app->concord->registerModel(
            InvoiceContract::class,
            Invoice::class
        );


        $this->app->concord->registerModel(
            InvoiceItemContract::class,
            InvoiceItem::class
        );

        $this->app->concord->registerModel(
            OrderContract::class,
            Order::class
        );

        $this->app->concord->registerModel(
            OrderAddressContract::class,
            OrderAddress::class
        );

        $this->app->concord->registerModel(
            OrderCommentContract::class,
            OrderComment::class
        );

        $this->app->concord->registerModel(
            OrderItemContract::class,
            OrderItem::class
        );


        $this->app->concord->registerModel(
            OrderPaymentContract::class,
            OrderPayment::class
        );

        $this->app->concord->registerModel(
            OrderTransactionContract::class,
            OrderTransaction::class
        ); 
        
        $this->app->concord->registerModel(
            RefundContract::class,
            Refund::class
        );

        $this->app->concord->registerModel(
            RefundItemContract::class,
            RefundItem::class
        );


        $this->app->concord->registerModel(
            ShipmentContract::class,
            Shipment::class
        );

        $this->app->concord->registerModel(
            ShipmentItemContract::class,
            ShipmentItem::class
        );

        
    }
}
