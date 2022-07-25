<?php

namespace berthott\ApiCache\Tests\Feature;

use berthott\ApiCache\Facades\ApiCache;
use berthott\ApiCache\Http\Middleware\ApiCacheMiddleware;
use berthott\ApiCache\Listeners\FlushApiCache;
use berthott\ApiCache\Tests\DummyDummy;
use berthott\ApiCache\Tests\TestCase;
use berthott\ApiCache\Tests\TestEvent;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;

class ApiCacheTest extends TestCase
{
    public function test_the_test_setup(): void
    {
        $this->assertContains('dummy_dummies.test', array_keys(Route::getRoutes()->getRoutesByName()));
        $this->get(route('dummy_dummies.test', ['dummy_dummy' => '1']))->assertSeeText('dummy_dummies');
    }

    public function test_route_caches(): void
    {
        $route = route('dummy_dummies.test', ['dummy_dummy' => '1', 'some_more_args' => 'hallo']);
        ApiCache::shouldReceive('get')
            ->withSomeOfArgs('api/alongtesturl/dummy_dummies/1a:1:{s:14:"some_more_args";s:5:"hallo";}', 'dummy_dummies')
            ->andReturn(new Response('cached Value'));
        $this->get($route)->assertSeeText('cached Value');
    }

    public function test_route_caches_ignore(): void
    {
        ApiCache::shouldReceive('get')->times(0);
        $this->get(route('dummy_dummies.ignore'));
    }

    public function test_flush_caches(): void
    {
        ApiCache::spy();
        $dummy = DummyDummy::create(['name' => 'test']);
        ApiCache::shouldHaveReceived('flush')->with('dummy_dummies')->once();
        ApiCache::shouldHaveReceived('flush')->with('dummy_dummy_dependencies')->once();
        $dummy->name = 'changed';
        ApiCache::shouldHaveReceived('flush')->with('dummy_dummies')->once();
        ApiCache::shouldHaveReceived('flush')->with('dummy_dummy_dependencies')->once();
        $dummy->delete();
        ApiCache::shouldHaveReceived('flush')->with('dummy_dummies');
        ApiCache::shouldHaveReceived('flush')->with('dummy_dummy_dependencies');
    }

    public function test_flush_caches_listener(): void
    {
        ApiCache::spy();
        DummyDummy::create(['name' => 'test']);
        ApiCache::shouldHaveReceived('flush')->with('dummy_dummies')->once();
        ApiCache::shouldHaveReceived('flush')->with('dummy_dummy_dependencies')->once();
        (new FlushApiCache())->handle(new TestEvent(DummyDummy::class));
        ApiCache::shouldHaveReceived('flush')->with('dummy_dummies')->twice();
        ApiCache::shouldHaveReceived('flush')->with('dummy_dummy_dependencies')->twice();
    }
}
