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
        if ($this->app->runningInConsole()) {

            // Publishing the configuration file.
            $this->publishes([
                __DIR__.'/../config/laravelmakeservice.php' => config_path('laravelmakeservice.php'),
            ], 'laravelmakeservice.config');

            $this->commands([
                ServiceMakeCommand::class
            ]);
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/laravelmakeservice.php', 'laravelmakeservice');

        // Register the service the package provides.
        $this->app->singleton('laravelmakeservice', function ($app) {
            return new LaravelMakeService;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['laravelmakeservice'];
    }
}