<?php

namespace berthott\ApiCache\Listeners;

use Facades\berthott\ApiCache\Services\ApiCacheLogService;

/**
 * An event listener to log the cache.
 * 
 * Can be registered with cache events.
 */
class LogCache
{
    /**
     * Handle the event.
     */
    public function handle(mixed $event)
    {
        ApiCacheLogService::log(class_basename(get_class($event)), key: $event->key, tags: $event->tags);
    }
}