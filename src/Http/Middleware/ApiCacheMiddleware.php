<?php

namespace berthott\ApiCache\Http\Middleware;

use berthott\ApiCache\Facades\ApiCache;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class ApiCacheMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): mixed
    {
        if ($request->method() !== 'GET') {
            return $next($request);
        }
        
        return ApiCache::get($request->path().serialize($request->all()), function () use ($next, $request) {
            return $next($request);
        }, explode('.', $request->route()->getName())[0]);
    }
}
