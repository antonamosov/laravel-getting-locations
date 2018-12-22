<?php

namespace Antonamosov\LaravelGettingLocations;

use Illuminate\Support\ServiceProvider;

class LaravelGettingLocationsServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/getting-locations.php' => config_path('getting-locations.php'),
        ], 'config');
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/getting-locations.php', 'laravelgettinglocations');

        $this->app->singleton('laravelgettinglocations', function ($app) {

            $service = config('getting-locations.service');

            return new LaravelGettingLocations(new $service(new CurlClient()));
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['laravelgettinglocations'];
    }
}