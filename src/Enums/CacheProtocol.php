<?php

namespace Redberry\LaravelCloudSdk\Enums;

enum CacheProtocol: string
{
    case Redis = 'redis';
    case Fake = 'fake';
}
