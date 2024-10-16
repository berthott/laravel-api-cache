<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Enable the API caching
    |--------------------------------------------------------------------------
    */

    'enabled' => env('CACHE_API', false),

    /*
    |--------------------------------------------------------------------------
    | Redis key
    |--------------------------------------------------------------------------
    |
    | A key to add to each redis cache to be able to use a single redis server
    | and avoid name clashes.
    */

    'key' => env('CACHE_API_KEY', env('APP_NAME', 'laravel')),

    /*
    |--------------------------------------------------------------------------
    | Ignored Routes
    |--------------------------------------------------------------------------
    | 
    | An array of route names to be ignored from the cache.
    */

    'ignoreRoutes' => [],

    /*
    |--------------------------------------------------------------------------
    | Include Routes
    |--------------------------------------------------------------------------
    | 
    | An array of route names to be included in the cache.
    | GET requests are included by default, here you should specify routes
    | that use the POST / PUT / DELETE methods. 
    | A wildcard can be used to include all actions.
    |
    | Caution: does it really make sense to cache these routes?
    */

    'includeRoutes' => [],

    /*
    |--------------------------------------------------------------------------
    | Cache Lifetime
    |--------------------------------------------------------------------------
    |
    | The lifetime of the cache in days.
    */

    'lifetime' => 14,
];
