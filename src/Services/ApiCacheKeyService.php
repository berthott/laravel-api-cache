<?php

namespace berthott\ApiCache\Services;

use Illuminate\Support\Str;

/**
 * Helper class.
 */
class ApiCacheKeyService
{
    /**
     * Append the application key to the cache key.
     */
    public function getCacheKey(string $key): string
    {
        return  Str::replace(' ', '_', config('api-cache.key')).'_'.$key;
    }
}
