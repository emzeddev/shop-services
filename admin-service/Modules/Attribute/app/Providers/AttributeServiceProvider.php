<?php

namespace Modules\Attribute\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Nwidart\Modules\Traits\PathNamespace;
// use Modules\Attribute\Proxies\AttributeProxy;
use Modules\Attribute\Contracts\Attribute as AttributeContract;
use Modules\Attribute\Models\Attribute;

use Modules\Attribute\Contracts\AttributeFamily as AttributeFamilyContract;
use Modules\Attribute\Models\AttributeFamily;

use Modules\Attribute\Contracts\AttributeGroup as AttributeGroupContract;
use Modules\Attribute\Models\AttributeGroup;

use Modules\Attribute\Contracts\AttributeOption as AttributeOptionContract;
use Modules\Attribute\Models\AttributeOption;

use Modules\Attribute\Contracts\AttributeOptionTranslation as AttributeOptionTranslationContract;
use Modules\Attribute\Models\AttributeOptionTranslation;

use Modules\Attribute\Contracts\AttributeTranslation as AttributeTranslationContract;
use Modules\Attribute\Models\AttributeTranslation;

class AttributeServiceProvider extends ServiceProvider
{
    use PathNamespace;

    protected string $name = 'Attribute';

    protected string $nameLower = 'attribute';

    // protected $models = [
    //     AttributeContract::class => Attribute::class,
    // ];

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
        $this->publishes([
            module_path($this->name, 'config/config.php') => config_path($this->nameLower.'.php')
        ], 'config');
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
            AttributeContract::class,
            Attribute::class
        );

        $this->app->concord->registerModel(
            AttributeFamilyContract::class,
            AttributeFamily::class
        );

        $this->app->concord->registerModel(
            AttributeGroupContract::class,
            AttributeGroup::class
        );

        $this->app->concord->registerModel(
            AttributeOptionContract::class,
            AttributeOption::class
        );

        $this->app->concord->registerModel(
            AttributeOptionTranslationContract::class,
            AttributeOptionTranslation::class
        );

        $this->app->concord->registerModel(
            AttributeTranslationContract::class,
            AttributeTranslation::class
        );
    }
}
