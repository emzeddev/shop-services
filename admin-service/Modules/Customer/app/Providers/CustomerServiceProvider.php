<?php

namespace Modules\Customer\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Nwidart\Modules\Traits\PathNamespace;

use Modules\Customer\Contracts\CompareItem as CompareItemContract;
use Modules\Customer\Models\CompareItem;

use Modules\Customer\Contracts\Customer as CustomerContract;
use Modules\Customer\Models\Customer;

use Modules\Customer\Contracts\CustomerAddress as CustomerAddressContract;
use Modules\Customer\Models\CustomerAddress;

use Modules\Customer\Contracts\CustomerGroup as CustomerGroupContract;
use Modules\Customer\Models\CustomerGroup;

use Modules\Customer\Contracts\CustomerNote as CustomerNoteContract;
use Modules\Customer\Models\CustomerNote;

use Modules\Customer\Contracts\Wishlist as WishlistContract;
use Modules\Customer\Models\Wishlist;

class CustomerServiceProvider extends ServiceProvider
{
    use PathNamespace;

    protected string $name = 'Customer';

    protected string $nameLower = 'customer';

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
            CompareItemContract::class,
            CompareItem::class
        );

        $this->app->concord->registerModel(
            CustomerContract::class,
            Customer::class
        );

        $this->app->concord->registerModel(
            CustomerAddressContract::class,
            CustomerAddress::class
        );

        $this->app->concord->registerModel(
            CustomerGroupContract::class,
            CustomerGroup::class
        );

        $this->app->concord->registerModel(
            CustomerNoteContract::class,
            CustomerNote::class
        );

        $this->app->concord->registerModel(
            WishlistContract::class,
            Wishlist::class
        );
    }
}
