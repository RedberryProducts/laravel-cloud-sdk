<?php

namespace Redberry\LaravelCloudSdk\Enums;

enum MetricPeriod: string
{
    case SixHours = '6h';
    case TwentyFourHours = '24h';
    case ThreeDays = '3d';
    case SevenDays = '7d';
    case ThirtyDays = '30d';
}
