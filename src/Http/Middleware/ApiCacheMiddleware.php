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
        if ($request->method() !== 'GET' || in_array($request->route()->getName(), config('api-cache.ignoreRoutes'))) {
            return $next($request);
        }
        
        return ApiCacheService::get(
            key: $this->buildCacheKey($request), 
            callback: fn() => $next($request), 
            tags: ApiCacheKeyService::getCacheKey(explode('.', $request->route()->getName())[0])
        );
    }

    /**
     * Build the cache key.
     */
    private function buildCacheKey(Request $request): string
    {
        return $request->path().':'.hash('sha256', serialize($request->all()));
    }
}
