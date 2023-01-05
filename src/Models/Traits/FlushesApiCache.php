<?php

namespace berthott\ApiCache\Models\Traits;

use berthott\ApiCache\Facades\ApiCache;
use Illuminate\Support\Str;

trait FlushesApiCache
{
    /**
     * Returns an array of dependencies to flush.
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
        ApiCache::flush(static::flushKey());
        foreach (static::cacheDependencies() as $dependency) {
            ApiCache::flush($dependency);
        }
    }
}
