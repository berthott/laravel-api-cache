![test workflow](https://github.com/berthott/laravel-api-cache/actions/workflows/test.yml/badge.svg)

# Laravel-API-Cache

A helper for caching complete API Responses. Easily cache the complete response of you Laravel API.

## Installation

```sh
$ composer require berthott/laravel-api-cache
```

## Usage

* The package automatically caches all responses to GET requests when installed and enabled.
  * The package assumes, that your routes are named according to Laravels Route::apiResource helper (`tablename.method`).
  * Responses are grouped via the table name (more specifically the first part of the route name, so custom route names are supported too).
* To automatically flush the cache corresponding to your model add the `FlushesApiCache` Trait to your model.
  * This will flush the cache for any model creation, update or deletion.
  * To also flush dependent models override the Traits `cacheDependencies` method and return a list of related route names.
* A `FlushApiCache` event listener is available to be connected with custom model events.

## Options

To change the default options use
```sh
$ php artisan vendor:publish --provider="berthott\ApiCache\ApiCacheServiceProvider" --tag="config"
```
* `enabled`: Enable the API caching. Default to `env('CACHE_API', false)`.
* `ignoreRoutes`: An array of route names to be ignored from the cache. Defaults to `[]`.
* `lifetime`: The lifetime of the cache in days. Defaults to `14`.
* `key`: A key to add to each redis cache to be able to use a single redis server and avoid name clashes. Defaults to`env('CACHE_API_KEY', env('APP_NAME', 'laravel'))`.

## Architecture

* The actual caching is done in `ApiCacheService`
* A store-key is used to connect requests / cached responses to a specific model / entity
* An `ApiCacheMiddleware` intercepts all http requests and calls `ApiCacheService`
* The `FlushesApiCache` trait observes creating / updating / deleting models and will clear the cache for the corresponding store 

## Compatibility

Tested with Laravel 10.x.

## License

See [License File](license.md). Copyright © 2023 Jan Bladt.