<?php

namespace berthott\ApiCache\Models\Traits;

use Facades\berthott\ApiCache\Services\ApiCacheLogService;
use Facades\berthott\ApiCache\Services\ApiCacheKeyService;
use Facades\berthott\ApiCache\Services\ApiCacheService;
use Illuminate\Support\Str;

/**
 * Trait to add auto flushing functionality.
 */
trait FlushesApiCache
{
    /**
     * Returns an array of dependencies to flush.
     *  
     * **optional**
     * 
     * Defaults to `[]`.
     * 
     * @api
     */
    public static function cacheDependencies(): array
    {
        return [];
    }

    /**
     * Register Cache Flushing
     */
    public static function bootFlushesApiCache()
    {
        static::created(function () {
            ApiCacheLogService::log('Created '.class_basename(get_called_class()));
            static::flushCache();
        });

        static::updated(function () {
            ApiCacheLogService::log('Updated '.class_basename(get_called_class()));
            static::flushCache();
        });

        static::deleted(function () {
            ApiCacheLogService::log('Deleted '.class_basename(get_called_class()));
            static::flushCache();
        });
    }

    /**
     * The entity table name of the model.
     */
    protected static function flushKey(): string
    {
        return ApiCacheKeyService::getCacheKey(Str::snake(Str::pluralStudly(class_basename(get_called_class()))));
    }

    /**
     * Flush own and dependencies cache.
     */
    public static function flushCache()
    {
        ApiCacheService::flush(static::flushKey());
        foreach (static::cacheDependencies() as $dependency) {
            ApiCacheLogService::log('Flush dependency '.class_basename(get_called_class()).': '.$dependency);
            ApiCacheService::flush(ApiCacheKeyService::getCacheKey($dependency));
        }
    }
}
