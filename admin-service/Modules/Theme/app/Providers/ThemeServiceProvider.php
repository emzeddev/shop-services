<?php

namespace Modules\Theme\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Nwidart\Modules\Traits\PathNamespace;
use Modules\Theme\Themes;

use Modules\Theme\Contracts\ThemeCustomization as ThemeCustomizationContract;
use Modules\Theme\Models\ThemeCustomization;

use Modules\Theme\Contracts\ThemeCustomizationTranslation as ThemeCustomizationTranslationContract;
use Modules\Theme\Models\ThemeCustomizationTranslation;

class ThemeServiceProvider extends ServiceProvider
{
    use PathNamespace;

    protected string $name = 'Theme';

    protected string $nameLower = 'theme';

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

        $this->app->singleton('themes', function () {
            return new Themes;
        });

        $this->app->singleton('view.finder', function ($app) {
            return new \Modules\Theme\ThemeViewFinder(
                $app['files'],
                $app['config']['view.paths'],
                null
            );
        });
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
            $this->loadTranslationsFrom(module_path($this->name, 'lang'), $this->nameLower);
            $this->loadJsonTranslationsFrom(module_path($this->name, 'lang'));
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
            ThemeCustomizationContract::class,
            ThemeCustomization::class
        );

        $this->app->concord->registerModel(
            ThemeCustomizationTranslationContract::class,
            ThemeCustomizationTranslation::class
        );
    }
}
