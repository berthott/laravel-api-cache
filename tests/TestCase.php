<?php

namespace berthott\ApiCache\Tests;

use berthott\ApiCache\ApiCacheServiceProvider;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        return [
            ApiCacheServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        Config::set('api-cache.enabled', true);
        Config::set('api-cache.key', 'test key');
        Config::set('api-cache.ignoreRoutes', ['dummy_dummies.ignore']);
        Config::set('api-cache.includeRoutes', [
            'dummy_dummies.include',
            '*.include_me_too',
        ]);
        $this->setUpTables();
        Route::prefix('api')
            ->middleware('api')
            ->group(function () {
                Route::get('/alongtesturl/dummy_dummies/{dummy_dummy}', function () {
                    return 'dummy_dummies';
                })->name('dummy_dummies.test');
                Route::get('/dummy_dummies', function () {
                    return 'hello';
                })->name('dummy_dummies.ignore');
                Route::post('/dummy_dummies/include', function () {
                    return 'hello';
                })->name('dummy_dummies.include');
                Route::post('/dummy_dummies/include_me_too', function () {
                    return 'hello';
                })->name('dummy_dummies.include_me_too');
            });
    }

    private function setUpTables(): void
    {
        Schema::create('dummy_dummies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->timestamps();
        });
    }
}
