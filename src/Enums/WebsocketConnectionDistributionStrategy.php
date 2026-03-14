<?php

namespace App\Enums\LaravelCloud;

enum WebsocketConnectionDistributionStrategy: string
{
    case EVENLY = 'evenly';
    case CUSTOM = 'custom';
}
