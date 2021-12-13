<?php

namespace berthott\ApiCache\Tests;

use berthott\ApiCache\ApiCacheServiceProvider;
use Illuminate\Database\Schema\Blueprint;
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
        Route::get('dummies', function () {
            return 'dummies';
        })->name('dummies.show');
        Route::get('/along/url/dummy_dependencies/{dummy_dependency}', function (DummyDependency $dummy) {
            return 'dummy_dependencies';
        })->name('dummy_dependencies.show');
    }

    private function setUpTables(): void
    {
        Schema::create('dummies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
        });
        Schema::create('dummy_dependencies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
        });
    }
}
