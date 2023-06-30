<?php

namespace berthott\ApiCache\Http\Middleware;

use Facades\berthott\ApiCache\Services\ApiCacheService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ApiCacheMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): mixed
    {
        if ($request->method() !== 'GET' || in_array($request->route()->getName(), config('api-cache.ignoreRoutes'))) {
            return $next($request);
        }
        
        return ApiCacheService::get($request->path().serialize($request->all()), function () use ($next, $request) {
            return $next($request);
        }, Str::replace(' ', '_', config('api-cache.key')).'_'.explode('.', $request->route()->getName())[0]);
    }
}
