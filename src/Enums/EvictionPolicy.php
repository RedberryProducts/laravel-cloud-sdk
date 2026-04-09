<?php

namespace Redberry\LaravelCloudSdk\Enums;

enum EvictionPolicy: string
{
    case AllKeysLru = 'allkeys-lru';
    case NoEviction = 'noeviction';
    case VolatileLru = 'volatile-lru';
    case AllKeysRandom = 'allkeys-random';
    case VolatileRandom = 'volatile-random';
    case VolatileTtl = 'volatile-ttl';
    case AllKeysLfu = 'allkeys-lfu';
    case VolatileLfu = 'volatile-lfu';
}
