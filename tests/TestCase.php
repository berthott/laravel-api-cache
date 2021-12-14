<?php

namespace berthott\ApiCache\Tests;

use berthott\ApiCache\ApiCacheServiceProvider;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Routing\Router;
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
        $this->setUpTables();
        Route::prefix('api')
            ->middleware('api')
            ->group(function () {
                Route::get('/alongtesturl/dummy_dummies/{dummy_dummy}', function () {
                    return 'dummy_dummies';
                })->name('dummy_dummies.test');
                Route::get('/along/url/dummy_dummies/{dummy_dummy}', function (DummyDummy $dummy) {
                    return $dummy;
                })->name('dummy_dummies.show');
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
