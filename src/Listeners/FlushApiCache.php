<?php

namespace berthott\ApiCache\Listeners;

use berthott\ApiCache\Facades\ApiCache;

class FlushApiCache
{
    public function handle($event)
    {
        if (property_exists($event, 'model') && method_exists($event->model, 'flushCache')) {
            $event->model::flushCache();
        } else {
            ApiCache::flush();
        }
    }
}