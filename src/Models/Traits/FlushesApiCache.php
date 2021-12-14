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
        self::created(function () {
            self::flushCache();
        });

        self::updated(function () {
            self::flushCache();
        });

        self::deleted(function () {
            self::flushCache();
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
    private static function flushCache()
    {
        ApiCache::flush(self::flushKey());
        foreach (self::cacheDependencies() as $dependency) {
            ApiCache::flush($dependency);
        }
    }
}
