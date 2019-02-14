<?php

namespace BeyondCode\LaravelTinkerServer;

use BeyondCode\LaravelTinkerServer\Console\TinkerServerCommand;
use Illuminate\Support\ServiceProvider;

class LaravelTinkerServerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('laravel-tinker-server.php'),
            ], 'config');

            // Registering package commands.
            $this->commands([
                TinkerServerCommand::class
            ]);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'laravel-tinker-server');
    }
}
