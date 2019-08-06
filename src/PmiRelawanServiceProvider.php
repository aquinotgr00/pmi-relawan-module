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
}
