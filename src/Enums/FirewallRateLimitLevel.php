<?php

namespace Redberry\LaravelCloudSdk\Enums;

enum FirewallRateLimitLevel: string
{
    case Challenge = 'challenge';
    case Throttle = 'throttle';
    case Ban = 'ban';
}
