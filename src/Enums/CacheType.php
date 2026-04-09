<?php

namespace Redberry\LaravelCloudSdk\Enums;

enum CacheType: string
{
    case UpstashRedis = 'upstash_redis';
    case LaravelValkey = 'laravel_valkey';
}
