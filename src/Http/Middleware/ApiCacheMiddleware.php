<?php

namespace berthott\ApiCache\Http\Middleware;

use Facades\berthott\ApiCache\Services\ApiCacheService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
            key: $request->path().serialize($request->all()), 
            cb: fn() => $next($request), 
            tag: Str::replace(' ', '_', config('api-cache.key')).'_'.explode('.', $request->route()->getName())[0]
        );
    }
}
