<?php

namespace Redberry\LaravelCloudSdk\Enums;

enum CacheType: string
{
    case UPSTASH_REDIS = 'upstash_redis';
    case LARAVEL_VALKEY = 'laravel_valkey';
}
