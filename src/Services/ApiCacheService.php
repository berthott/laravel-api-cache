<?php

namespace berthott\ApiCache\Services;

use Facades\berthott\ApiCache\Services\ApiCacheLogService;
use Illuminate\Support\Facades\Cache;
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
    public function get(string $key, callable $callback, mixed $tags = false)
    {
        $store = $tags ? Cache::tags($tags) : Cache::getStore();
        if ($store->has($key)) {
            ApiCacheLogService::log('Get', $key, $tags);
            return unserialize(gzuncompress($store->get($key)));
        }

        $response = $callback();
        try {
            $store->put($key, gzcompress(serialize($response)), now()->addDays(config('api-cache.lifetime')));
            ApiCacheLogService::log('Put', $key, $tags);
        } catch (Throwable $error) {
            ApiCacheLogService::log('Error while putting', $key, $tags);
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
        ApiCacheLogService::log('Flushed', tags: $tags);
    }
}
