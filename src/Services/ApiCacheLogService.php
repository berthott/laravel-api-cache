<?php

namespace berthott\ApiCache\Services;

use Illuminate\Support\Facades\Log;

/**
 * Helper class.
 */
class ApiCacheLogService
{
    /**
     * Log the message to the api-cache channel.
     */
    public function log(string $message, ?string $key = null, mixed $tags = null, mixed $data = null)
    {
        Log::channel('api-cache')->info($message, [
            ...($key ? ['key' => $key] : []), 
            'tags' => $tags?: 'none', 
            ...($data ? ['data' => $data] : [])]);
    }
}
