<?php

namespace berthott\ApiCache\Tests;

use berthott\ApiCache\Models\Traits\FlushesApiCache;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DummyDummy extends Model
{
    use FlushesApiCache;

    /**
     * Returns an array of dependencies to flush.
     */
    public static function cacheDependencies(): array
    {
        return [
            'dummy_dummy_dependencies'
        ];
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];
}
