<?php

namespace berthott\ApiCache\Listeners;

use Facades\berthott\ApiCache\Services\ApiCacheService;

class FlushApiCache
{
    public function handle($event)
    {
        if (property_exists($event, 'model') && method_exists($event->model, 'flushCache')) {
            $event->model::flushCache();
        } else {
            ApiCacheService::flush();
        }
    }
}