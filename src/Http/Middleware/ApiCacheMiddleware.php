<?php

namespace berthott\ApiCache\Http\Middleware;

use Facades\berthott\ApiCache\Services\ApiCacheKeyService;
use Facades\berthott\ApiCache\Services\ApiCacheService;
use Closure;
use Illuminate\Http\Request;

/**
 * Middleware to handle the caching.
 */
class ApiCacheMiddleware
{
    /**
     * Handle an incoming request.
     * 
     * Only non-ignored GET requests will be handled.
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $name = $request->route()->getName();
        $method = $request->method();
        if (!$this->includeRoute($name) && $method !== 'GET' || $this->ignoreRoute($name)) {
            return $next($request);
        }
        
        return ApiCacheService::get(
            key: $this->buildCacheKey($request), 
            callback: fn() => $next($request), 
            tags: ApiCacheKeyService::getCacheKey(explode('.', $name)[0])
        );
    }

    /**
     * Check if the route should be included in the cache.
     */
    private function includeRoute(string $name): bool
    {
        if (in_array($name, config('api-cache.includeRoutes'))) {
            return true;
        }

        $ret = false;
        foreach (config('api-cache.includeRoutes') as $route) {
            if (str_starts_with($route, '*') && explode('.', $name)[1] === explode('.', $route)[1]) {
                $ret = true;
                break;
            }
        }

        return $ret;
    }

    /**
     * Check if the route should be ignored by the cache.
     */
    private function ignoreRoute(string $name): bool
    {
        return in_array($name, config('api-cache.ignoreRoutes'));
    }

    /**
     * Build the cache key.
     */
    private function buildCacheKey(Request $request): string
    {
        return $request->path().':'.hash('sha256', serialize($request->all()));
    }
}
