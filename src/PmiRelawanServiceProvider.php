<?php

namespace BajakLautMalaka\PmiRelawan;

use Illuminate\Support\ServiceProvider;
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
    public function boot(Router $router)
    {
        $this->loadRoutes($router);
        $this->loadViews();
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
