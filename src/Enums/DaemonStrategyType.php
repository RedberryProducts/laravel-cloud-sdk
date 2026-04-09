<?php

namespace Redberry\LaravelCloudSdk\Enums;

enum DaemonStrategyType: string
{
    case None = 'none';
    case GrowthRate = 'growth_rate';
    case QueueSize = 'queue_size';
}
