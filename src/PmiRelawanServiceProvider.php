<?php

namespace BajakLautMalaka\PmiRelawan;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;
use Illuminate\Routing\RouteRegistrar as Router;

class PmiRelawanServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(Factory $factory, Router $router)
    {
        $this->loadConfig();
        $this->loadMigrationsAndFactories($factory);
        $this->loadRoutes($router);
        $this->loadViews();
    }

    /**
     * Register any load config.
     *
     * @return void
     */
    private function loadConfig()
    {
        $path = __DIR__ . '/../config/volunteer.php';
        $this->mergeConfigFrom($path, 'volunteer');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                $path => config_path('volunteer.php'),
            ], 'volunteer:config');
        }
    }

    /**
     * Register any load migrations & factories from package volunteers.
     *
     * @return void
     */
    private function loadMigrationsAndFactories(Factory $factory): void
    {
        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

            $factory->load(__DIR__ . '/../database/factories');
        }
    }

    /**
     * Register any load routes.
     */
    private function loadRoutes(Router $router): void
    {
        $router->prefix('api')
               ->namespace('BajakLautMalaka\PmiRelawan\Http\Controllers\Api')
               ->middleware(['api'])
               ->group(function () {
                   $this->loadRoutesFrom(__DIR__.'/../routes/api.php');
               });
    }

    /**
     * Register any load view.
     *
     * @return void
     */
    private function loadViews()
    {
        $path = __DIR__.'/../resources/views';
        $this->loadViewsFrom($path, 'volunteer');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                $path => resource_path('views/bajaklautmalaka/volunteer'),
            ], 'volunteer:views');
        }
    }
}
