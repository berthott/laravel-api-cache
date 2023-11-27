<?php

namespace berthott\ApiCache\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

/**
 * Service to handle caching.
 */
class ApiCacheService
{
    /**
     * Get the response for a given key.
     * 
     * If a value is cached under the given key it is returned, otherwise 
     * a new value is generated via the callback and stored under the key.
     * 
     * Optionally tags can be used to cluster keys.
     */
    public function get(string $cacheKey, callable $callback, mixed $tags = false)
    {
        $store = $tags ? Cache::tags($tags) : Cache::getStore();
        if ($store->has($cacheKey)) {
            return $store->get($cacheKey);
        }

        $response = $callback();
        try {
            $store->put($cacheKey, $response, now()->addDays(config('api-cache.lifetime')));
        } catch (Throwable $error) {
            // just catch the error
        }
        return $response;
    }

    /**
     * Flush the cache.
     */
    public function flush(mixed $tags = false)
    {
        $store = $tags ? Cache::tags($tags) : Cache::getStore();
        $store->flush();
        Log::channel('api-cache')->info('Flushed', ['tags' => $tags ?: 'completely']);
    }

    /**
     * Flush the cache.
     */
    public function getCacheKey(string $key): string
    {
        return Str::replace(' ', '_', config('api-cache.key')).'_'.$key;
    }
}
