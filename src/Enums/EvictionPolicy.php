<?php

namespace App\Enums\LaravelCloud;

enum EvictionPolicy: string
{
    case ALLKEYS_LRU = 'allkeys-lru';
    case NOEVICTION = 'noeviction';
    case VOLATILE_LRU = 'volatile-lru';
    case ALLKEYS_RANDOM = 'allkeys-random';
    case VOLATILE_RANDOM = 'volatile-random';
    case VOLATILE_TTL = 'volatile-ttl';
    case ALLKEYS_LFU = 'allkeys-lfu';
    case VOLATILE_LFU = 'volatile-lfu';
}
