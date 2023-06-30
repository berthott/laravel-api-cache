<?php

namespace berthott\ApiCache\Models\Traits;

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
            static::flushCache();
        });

        static::updated(function () {
            static::flushCache();
        });

        static::deleted(function () {
            static::flushCache();
        });
    }

    /**
     * The entity table name of the model.
     */
    protected static function flushKey(): string
    {
        return Str::snake(Str::pluralStudly(class_basename(get_called_class())));
    }

    /**
     * Flush own and dependencies cache.
     */
    public static function flushCache()
    {
        ApiCacheService::flush(static::flushKey());
        foreach (static::cacheDependencies() as $dependency) {
            ApiCacheService::flush($dependency);
        }
    }
}
