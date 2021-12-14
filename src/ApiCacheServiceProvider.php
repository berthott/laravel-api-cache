<?php

namespace berthott\ApiCache;

use berthott\ApiCache\Http\Middleware\ApiCacheMiddleware;
use berthott\ApiCache\Services\ApiCacheService;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;

class ApiCacheServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // bind singletons
        $this->app->singleton('ApiCache', function () {
            return new ApiCacheService();
        });

        // add config
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'api-cache');


    }

    /**
     * Bootstrap services.
     */
    public function boot(Kernel $kernel): void
    {
        // publish config
        $this->publishes([
            __DIR__.'/../config/config.php' => config_path('api-cache.php'),
        ], 'config');

        // add middleware
        $router = app(Router::class);
        $router->pushMiddlewareToGroup('api', ApiCacheMiddleware::class);
    }
}
