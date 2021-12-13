<?php

namespace berthott\ApiCache\Tests;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Dummy
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];
}
