<?php

namespace App\Enums\LaravelCloud;

enum FirewallRateLimitLevel: string
{
    case Challenge = 'challenge';
    case Throttle = 'throttle';
    case Ban = 'ban';
}
