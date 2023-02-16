# Laravel-API-Cache - A helper for caching complete API Responses

Easily cache the complete response of you laravel API.

## Installation

```
$ composer require berthott/laravel-api-cache
```

## Usage

* The package automatically caches all responses to GET requests when installed and enabled.
  * The package assumes, that your routes are named according to Laravels Route::apiResource helper ('tablename.method').
  * Responses are grouped via the table name (more specifically the first part of the route name, so custom route names are supported too).
* To automatically flush the cache corresponding to your model add the `FlushesApiCache` Trait to your model.
  * This will flush the cache for any model creation, update or deletion.
  * To also flush dependent models override the Traits `cacheDependencies` method and return a list of related route names.

## Options

To change the default options use
```
$ php artisan vendor:publish --provider="berthott\ApiCache\ApiCacheServiceProvider" --tag="config"
```
* `enabled`: enables the feature and can be changed via CACHE_API. Defaults to `false`.
* `ignoreRoutes`: an array of route names to be ignored from the cache. Defaults to an empty array.
* `lifetime`: the lifetime of the cache. Defaults to `14`.

## Compatibility

Tested with Laravel 10.x.

## License

See [License File](license.md). Copyright © 2023 Jan Bladt.