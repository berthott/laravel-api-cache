<?php

namespace berthott\ApiCache\Tests\Feature;

use berthott\ApiCache\Listeners\FlushApiCache;
use Facades\berthott\ApiCache\Services\ApiCacheService;
use berthott\ApiCache\Tests\DummyDummy;
use berthott\ApiCache\Tests\TestCase;
use berthott\ApiCache\Tests\TestEvent;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;
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
        ApiCacheService::shouldReceive('get')
            ->withSomeOfArgs('api/alongtesturl/dummy_dummies/1:de8568fc32afff5fea91a8d3505eda1e56f42c30d16800ebe2f7fe88a715ceae', 'test_key_dummy_dummies')
            ->andReturn(new Response('cached Value'));
        $this->get($route)->assertSeeText('cached Value');
    }

    public function test_route_caches_ignore(): void
    {
        ApiCacheService::shouldReceive('get')->times(0);
        $this->get(route('dummy_dummies.ignore'));
    }

    public function test_route_caches_include(): void
    {
        ApiCacheService::shouldReceive('get')
            ->withSomeOfArgs('api/dummy_dummies/include:35786c7117b4e38d0f169239752ce71158266ae2f6e4aa230fbbb87bd699c0e3', 'test_key_dummy_dummies')
            ->andReturn(new Response('cached Value'));
        $this->post(route('dummy_dummies.include'))->assertSeeText('cached Value');
    }

    public function test_route_caches_include_wildcard(): void
    {
        ApiCacheService::shouldReceive('get')
            ->withSomeOfArgs('api/dummy_dummies/include_me_too:35786c7117b4e38d0f169239752ce71158266ae2f6e4aa230fbbb87bd699c0e3', 'test_key_dummy_dummies')
            ->andReturn(new Response('cached Value'));
        $this->post(route('dummy_dummies.include_me_too'))->assertSeeText('cached Value');
    }

    public function test_flush_caches(): void
    {
        ApiCacheService::spy();
        $dummy = DummyDummy::create(['name' => 'test']);
        ApiCacheService::shouldHaveReceived('flush')->with('test_key_dummy_dummies')->once();
        ApiCacheService::shouldHaveReceived('flush')->with('test_key_dummy_dummy_dependencies')->once();
        $dummy->name = 'changed';
        ApiCacheService::shouldHaveReceived('flush')->with('test_key_dummy_dummies')->once();
        ApiCacheService::shouldHaveReceived('flush')->with('test_key_dummy_dummy_dependencies')->once();
        $dummy->delete();
        ApiCacheService::shouldHaveReceived('flush')->with('test_key_dummy_dummies');
        ApiCacheService::shouldHaveReceived('flush')->with('test_key_dummy_dummy_dependencies');
    }

    public function test_flush_caches_listener(): void
    {
        ApiCacheService::spy();
        DummyDummy::create(['name' => 'test']);
        ApiCacheService::shouldHaveReceived('flush')->with('test_key_dummy_dummies')->once();
        ApiCacheService::shouldHaveReceived('flush')->with('test_key_dummy_dummy_dependencies')->once();
        (new FlushApiCache())->handle(new TestEvent(DummyDummy::class));
        ApiCacheService::shouldHaveReceived('flush')->with('test_key_dummy_dummies')->twice();
        ApiCacheService::shouldHaveReceived('flush')->with('test_key_dummy_dummy_dependencies')->twice();
    }

    public function test_cache_log(): void
    {
        $route = route('dummy_dummies.test', ['dummy_dummy' => '1']);
        $json = '{"key":"api/alongtesturl/dummy_dummies/1:35786c7117b4e38d0f169239752ce71158266ae2f6e4aa230fbbb87bd699c0e3","tags":["test_key_dummy_dummies"]}';
        $missed = 'CacheMissed '.$json;
        $written = 'KeyWritten '.$json;
        $hit = 'CacheHit '.$json;
        $forgotten = 'KeyForgotten '.$json;

        $today = now()->format('Y-m-d');
        $path = storage_path('logs').'/api-cache-'.$today.'.log';
        if (File::exists($path)) {
            File::delete($path);
        }

        // initial
        $this->get($route);

        $this->assertTrue(File::exists($path));

        $log = file_get_contents($path);
        $this->assertNotFalse(strpos($log, $missed));
        $this->assertNotFalse(strpos($log, $written));
        $this->assertFalse(strpos($log, $hit));
        $this->assertFalse(strpos($log, $forgotten));

        // hit
        $this->get($route);

        $this->assertTrue(File::exists($path));

        $log = file_get_contents($path);
        $this->assertNotFalse(strpos($log, $missed));
        $this->assertNotFalse(strpos($log, $written));
        $this->assertNotFalse(strpos($log, $hit));
        $this->assertFalse(strpos($log, $forgotten));
    }
}
