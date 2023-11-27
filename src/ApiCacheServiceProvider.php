<?php

namespace berthott\ApiCache;

use berthott\ApiCache\Http\Middleware\ApiCacheMiddleware;
use Illuminate\Contracts\Http\Kernel;
use berthott\ApiCache\Listeners\LogCache;
use Illuminate\Cache\Events\CacheHit;
use Illuminate\Cache\Events\CacheMissed;
use Illuminate\Cache\Events\KeyForgotten;
use Illuminate\Cache\Events\KeyWritten;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Event;
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

        // add listeners
        Event::listen([
            CacheHit::class,
            CacheMissed::class,
            KeyForgotten::class,
            KeyWritten::class
        ], LogCache::class);
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

        // register log channel
        $this->app->make('config')->set('logging.channels.api-cache', [
            'driver' => 'daily',
            'path' => storage_path('logs/api-cache.log'),
            'level' => 'debug',
        ]);
    }
}
