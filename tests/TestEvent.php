<?php

namespace berthott\ApiCache\Tests;

use Illuminate\Foundation\Events\Dispatchable;

class TestEvent
{
    use Dispatchable;

    public string $model;

    public function __construct(string $model) {
        $this->model = $model;
    }
}