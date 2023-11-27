<?php

namespace berthott\ApiCache\Listeners;

use Illuminate\Support\Facades\Log;

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
        Log::channel('api-cache')->info(class_basename(get_class($event)), ['key' => $event->key, 'tags' => $event->tags]);
    }
}