<?php

namespace Redberry\LaravelCloudSdk\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Redberry\LaravelCloudSdk\LaravelCloud
 */
class LaravelCloud extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Redberry\LaravelCloudSdk\LaravelCloud::class;
    }
}
