<?php

namespace App\Enums\LaravelCloud;

enum CacheProtocol: string
{
    case REDIS = 'redis';
    case FAKE = 'fake';
}
