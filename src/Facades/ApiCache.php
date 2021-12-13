<?php

namespace berthott\ApiCache\Facades;

use Illuminate\Support\Facades\Facade;

class ApiCache extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'ApiCache';
    }
}
