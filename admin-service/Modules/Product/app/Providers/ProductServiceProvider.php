<?php

namespace Modules\Product\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Nwidart\Modules\Traits\PathNamespace;

use Modules\Product\Contracts\Product as ProductContract;
use Modules\Product\Models\Product;

use Modules\Product\Contracts\ProductAttributeValue as ProductAttributeValueContract;
use Modules\Product\Models\ProductAttributeValue;

use Modules\Product\Contracts\ProductBundleOption as ProductBundleOptionContract;
use Modules\Product\Models\ProductBundleOption;

use Modules\Product\Contracts\ProductBundleOptionProduct as ProductBundleOptionProductContract;
use Modules\Product\Models\ProductBundleOptionProduct;

use Modules\Product\Contracts\ProductBundleOptionTranslation as ProductBundleOptionTranslationContract;
use Modules\Product\Models\ProductBundleOptionTranslation;

use Modules\Product\Contracts\ProductCustomerGroupPrice as ProductCustomerGroupPriceContract;
use Modules\Product\Models\ProductCustomerGroupPrice;

use Modules\Product\Contracts\ProductDownloadableLink as ProductDownloadableLinkContract;
use Modules\Product\Models\ProductDownloadableLink;

use Modules\Product\Contracts\ProductDownloadableLinkTranslation as ProductDownloadableLinkTranslationContract;
use Modules\Product\Models\ProductDownloadableLinkTranslation;

use Modules\Product\Contracts\ProductDownloadableSample as ProductDownloadableSampleContract;
use Modules\Product\Models\ProductDownloadableSample;

use Modules\Product\Contracts\ProductDownloadableSampleTranslation as ProductDownloadableSampleTranslationContract;
use Modules\Product\Models\ProductDownloadableSampleTranslation;

use Modules\Product\Contracts\ProductFlat as ProductFlatContract;
use Modules\Product\Models\ProductFlat;

use Modules\Product\Contracts\ProductGroupedProduct as ProductGroupedProductContract;
use Modules\Product\Models\ProductGroupedProduct;

use Modules\Product\Contracts\ProductImage as ProductImageContract;
use Modules\Product\Models\ProductImage;

use Modules\Product\Contracts\ProductInventory as ProductInventoryContract;
use Modules\Product\Models\ProductInventory;

use Modules\Product\Contracts\ProductInventoryIndex as ProductInventoryIndexContract;
use Modules\Product\Models\ProductInventoryIndex;

use Modules\Product\Contracts\ProductOrderedInventory as ProductOrderedInventoryContract;
use Modules\Product\Models\ProductOrderedInventory;

use Modules\Product\Contracts\ProductPriceIndex as ProductPriceIndexContract;
use Modules\Product\Models\ProductPriceIndex;

use Modules\Product\Contracts\ProductReview as ProductReviewContract;
use Modules\Product\Models\ProductReview;

use Modules\Product\Contracts\ProductReviewAttachment as ProductReviewAttachmentContract;
use Modules\Product\Models\ProductReviewAttachment;

use Modules\Product\Contracts\ProductSalableInventory as ProductSalableInventoryContract;
use Modules\Product\Models\ProductSalableInventory;

use Modules\Product\Contracts\ProductVideo as ProductVideoContract;
use Modules\Product\Models\ProductVideo;

class ProductServiceProvider extends ServiceProvider
{
    use PathNamespace;

    protected string $name = 'Product';

    protected string $nameLower = 'product';

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
            ProductContract::class,
            Product::class
        );  

        $this->app->concord->registerModel(
            ProductAttributeValueContract::class,
            ProductAttributeValue::class
        );  

        $this->app->concord->registerModel(
            ProductBundleOptionContract::class,
            ProductBundleOption::class
        );  

        $this->app->concord->registerModel(
            ProductBundleOptionProductContract::class,
            ProductBundleOptionProduct::class
        );  

        $this->app->concord->registerModel(
            ProductBundleOptionTranslationContract::class,
            ProductBundleOptionTranslation::class
        );  


        $this->app->concord->registerModel(
            ProductCustomerGroupPriceContract::class,
            ProductCustomerGroupPrice::class
        );  

        $this->app->concord->registerModel(
            ProductDownloadableLinkContract::class,
            ProductDownloadableLink::class
        );  

        $this->app->concord->registerModel(
            ProductDownloadableLinkTranslationContract::class,
            ProductDownloadableLinkTranslation::class
        );  

        $this->app->concord->registerModel(
            ProductDownloadableSampleContract::class,
            ProductDownloadableSample::class
        );  

        $this->app->concord->registerModel(
            ProductDownloadableSampleTranslationContract::class,
            ProductDownloadableSampleTranslation::class
        );  

        $this->app->concord->registerModel(
            ProductFlatContract::class,
            ProductFlat::class
        );  

        $this->app->concord->registerModel(
            ProductGroupedProductContract::class,
            ProductGroupedProduct::class
        );  

        $this->app->concord->registerModel(
            ProductImageContract::class,
            ProductImage::class
        );  

        $this->app->concord->registerModel(
            ProductInventoryContract::class,
            ProductInventory::class
        );  

        $this->app->concord->registerModel(
            ProductInventoryIndexContract::class,
            ProductInventoryIndex::class
        ); 

        $this->app->concord->registerModel(
            ProductOrderedInventoryContract::class,
            ProductOrderedInventory::class
        ); 

        $this->app->concord->registerModel(
            ProductPriceIndexContract::class,
            ProductPriceIndex::class
        ); 

        $this->app->concord->registerModel(
            ProductReviewContract::class,
            ProductReview::class
        ); 

        $this->app->concord->registerModel(
            ProductReviewAttachmentContract::class,
            ProductReviewAttachment::class
        ); 

        $this->app->concord->registerModel(
            ProductSalableInventoryContract::class,
            ProductSalableInventory::class
        ); 

        $this->app->concord->registerModel(
            ProductVideoContract::class,
            ProductVideo::class
        ); 
    }
}
