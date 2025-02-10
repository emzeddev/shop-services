<?php

namespace Modules\Checkout\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Nwidart\Modules\Traits\PathNamespace;

use Illuminate\Foundation\AliasLoader;


use Modules\Checkout\Contracts\Cart as CartContract;
use Modules\Checkout\Models\Cart;

use Modules\Checkout\Contracts\CartAddress as CartAddressContract;
use Modules\Checkout\Models\CartAddress;

use Modules\Checkout\Contracts\CartItem as CartItemContract;
use Modules\Checkout\Models\CartItem;

use Modules\Checkout\Contracts\CartPayment as CartPaymentContract;
use Modules\Checkout\Models\CartPayment;

use Modules\Checkout\Contracts\CartShippingRate as CartShippingRateContract;
use Modules\Checkout\Models\CartShippingRate;

class CheckoutServiceProvider extends ServiceProvider
{
    use PathNamespace;

    protected string $name = 'Checkout';

    protected string $nameLower = 'checkout';

    /**
     * Boot the application events.
     */
    public function boot(): void
    {
        include __DIR__.'/../Http/Helpers/helpers.php';
        
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

        $this->registerFacades();
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
     * Register cart as a singleton.
     */
    protected function registerFacades(): void
    {
        $loader = AliasLoader::getInstance();

        $loader->alias('cart', Cart::class);

        $this->app->singleton('cart', \Modules\Checkout\Cart::class);
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
            CartContract::class,
            Cart::class
        );

        $this->app->concord->registerModel(
            CartAddressContract::class,
            CartAddress::class
        );

        $this->app->concord->registerModel(
            CartItemContract::class,
            CartItem::class
        );

        $this->app->concord->registerModel(
            CartPaymentContract::class,
            CartPayment::class
        );

        $this->app->concord->registerModel(
            CartShippingRateContract::class,
            CartShippingRate::class
        );

    }
}
