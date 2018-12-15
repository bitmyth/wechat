<?php

namespace Bitmyth\Wechat;

use Bitmyth\Wechat\Console\InstallCommand;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

/**
 * Class WechatServiceProvider
 * @package Bitmyth\Wechat
 */
class WechatServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'wechat');

        $this->registerRoutes();

        if ($this->app->runningInConsole()) {
            $this->registerMigrations();

            $this->publishes([
                __DIR__ . '/../database/migrations' => database_path('migrations'),
            ], 'wechat-migrations');

            $this->commands([
                InstallCommand::class
            ]);
        }
    }

    /**
     * Register Passport's migration files.
     *
     * @return void
     */
    protected function registerMigrations()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->offerPublishing();
    }

    /**
     * Setup the resource publishing groups for Passport.
     *
     * @return void
     */
    protected function offerPublishing()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/wechat.php' => config_path('wechat.php'),
            ], 'wechat-config');

            $this->publishes([
                __DIR__ . '/../public' => public_path('vendor/bitmyth/wechat'),
            ], 'wechat-assets');
        }
    }

    /**
     * Register the package routes.
     *
     * @return void
     */
    private function registerRoutes()
    {
        Route::group($this->routeConfiguration(), function () {
            $this->loadRoutesFrom(__DIR__ . '/Http/routes.php');
        });
    }

    private function routeConfiguration()
    {
        return [
            'namespace' => 'Bitmyth\Wechat\Http\Controllers',
            'prefix' => 'api',
            'middleware' => 'api',
        ];
    }
}
