<?php

namespace berthott\ApiCache\Listeners;

use Facades\berthott\ApiCache\Services\ApiCacheService;

/**
 * An event listener to flush the cache.
 * 
 * Can be registered with model events.
 * If the event has a model set, `flushCache` is called on it.
 * If not, the whole cache will be flushed.
 * 
 * @api
 */
class FlushApiCache
{
    /**
     * Handle the event.
     */
    public function handle($event)
    {
        if (property_exists($event, 'model') && method_exists($event->model, 'flushCache')) {
            $event->model::flushCache();
        } else {
            ApiCacheService::flush();
        }
    }
}