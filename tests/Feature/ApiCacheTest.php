<?php

namespace berthott\ApiCache\Tests\Feature;

use berthott\ApiCache\Http\Middleware\ApiCacheMiddleware;
use berthott\ApiCache\Tests\TestCase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class ApiCacheTest extends TestCase
{
    public function test_the_test_setup(): void
    {
        $this->assertContains('dummy_dependencies.show', array_keys(Route::getRoutes()->getRoutesByName()));
        $this->get(route('dummy_dependencies.show', ['dummy_dependency' => '1']))->assertSeeText('dummy_dependencies');
    }

    /* public function test_route_caches(): void
    {

    } */
}
