<?php

namespace berthott\ApiCache\Services;

use Illuminate\Support\Facades\Cache;
use Throwable;

class ApiCacheService
{
    /**
     * If a value is cached under the given key it
     * is returned, otherwise a new value is generated
     * via the callback and stored under the key.
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
    }
}
