<?php

namespace berthott\ApiCache\Models\Traits;

use berthott\ApiCache\Facades\ApiCache;

trait FlushesApiCache
{
    /**
     * Returns an array of dependencies to flush.
     */
    public function cacheDependencies(): array
    {
        return [];
    }

    /**
     * Register Cache Flushing
     */
    public static function bootFlushesApiCache()
    {
        self::created(function () {
            $this->flushCache();
        });

        self::updated(function () {
            $this->flushCache();
        });

        self::deleted(function () {
            $this->flushCache();
        });
    }

    /**
     * Flush own and dependencies cache.
     */
    private function flushCache()
    {
        ApiCache::flush($this->getTable());
        foreach ($this->cacheDependencies() as $dependency) {
            ApiCache::flush($dependency);
        }
    }
}
