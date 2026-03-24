<?php

namespace Redberry\LaravelCloudSdk\Enums;

enum CacheProtocol: string
{
    case REDIS = 'redis';
    case FAKE = 'fake';
}
