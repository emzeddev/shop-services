<?php

namespace Modules\Core\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Nwidart\Modules\Traits\PathNamespace;
use Modules\Core\Core;
use Modules\Core\Facades\SystemConfig as SystemConfigFacade;
use Modules\Core\SystemConfig;
use Elastic\Elasticsearch\Client as ElasticSearchClient;
use Modules\Core\ElasticSearch;
use Modules\Core\Facades\ElasticSearch as ElasticSearchFacade;
use Illuminate\Foundation\AliasLoader;

use Modules\Core\Contracts\Channel as ChannelContract;
use Modules\Core\Models\Channel;

use Modules\Core\Contracts\Currency as CurrencyContract;
use Modules\Core\Models\Currency;

use Modules\Core\Contracts\Locale as LocaleContract;
use Modules\Core\Models\Locale;

use Modules\Core\Contracts\Address as AddressContract;
use Modules\Core\Models\Address;

use Modules\Core\Contracts\CoreConfig as CoreConfigContract;
use Modules\Core\Models\CoreConfig;

use Modules\Core\Contracts\Country as CountryContract;
use Modules\Core\Models\Country;

use Modules\Core\Contracts\CountryState as CountryStateContract;
use Modules\Core\Models\CountryState;

use Modules\Core\Contracts\CountryStateTranslation as CountryStateTranslationContract;
use Modules\Core\Models\CountryStateTranslation;

use Modules\Core\Contracts\CurrencyExchangeRate as CurrencyExchangeRateContract;
use Modules\Core\Models\CurrencyExchangeRate;

use Modules\Core\Contracts\SubscribersList as SubscribersListContract;
use Modules\Core\Models\SubscribersList;

use Modules\Core\Contracts\Visit as VisitContract;
use Modules\Core\Models\Visit;

class CoreServiceProvider extends ServiceProvider
{
    use PathNamespace;

    protected string $name = 'Core';

    protected string $nameLower = 'core';

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

    protected function registerFacades(): void
    {

        $loader = AliasLoader::getInstance();

        

        $this->app->singleton('core', function () {
            return app()->make(Core::class);
        });

        $loader->alias('system_config', SystemConfigFacade::class);

        $this->app->singleton('system_config', function () {
            return app()->make(SystemConfig::class);
        });


        $this->app->singleton('elasticsearch', function () {
            return new ElasticSearch;
        });

        $loader->alias('elasticsearch', ElasticSearchFacade::class);

        $this->app->singleton(ElasticSearchClient::class, function () {
            return app()->make('elasticsearch')->connection();
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
            module_path($this->name, 'config/config.php') => config_path($this->nameLower.'.php'),
            module_path($this->name, 'config/repository.php')    => config_path('repository.php')
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
            ChannelContract::class,
            Channel::class
        );

        $this->app->concord->registerModel(
            CurrencyContract::class,
            Currency::class
        );

        $this->app->concord->registerModel(
            LocaleContract::class,
            Locale::class
        );

        $this->app->concord->registerModel(
            AddressContract::class,
            Address::class
        );

        $this->app->concord->registerModel(
            CoreConfigContract::class,
            CoreConfig::class
        );

        $this->app->concord->registerModel(
            CountryContract::class,
            Country::class
        );

        $this->app->concord->registerModel(
            CountryStateContract::class,
            CountryState::class
        );

        $this->app->concord->registerModel(
            CountryStateTranslationContract::class,
            CountryStateTranslation::class
        );

        $this->app->concord->registerModel(
            CurrencyExchangeRateContract::class,
            CurrencyExchangeRate::class
        );

        $this->app->concord->registerModel(
            SubscribersListContract::class,
            SubscribersList::class
        );

        $this->app->concord->registerModel(
            VisitContract::class,
            Visit::class
        );
    }
}
