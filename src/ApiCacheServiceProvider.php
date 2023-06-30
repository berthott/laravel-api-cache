<?php

namespace berthott\ApiCache;

use berthott\ApiCache\Http\Middleware\ApiCacheMiddleware;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;

/**
 * Register the libraries features with the laravel application.
 */
class ApiCacheServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
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

        if (config('api-cache.enabled')) {
            // add middleware
            $router = app(Router::class);
            $router->pushMiddlewareToGroup('api', ApiCacheMiddleware::class);
        }
    }
}
