<?php

namespace App\Enums\LaravelCloud;

enum DaemonStrategyType: string
{
    case NONE = 'none';
    case GROWTH_RATE = 'growth_rate';
    case QUEUE_SIZE = 'queue_size';
}
