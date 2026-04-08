<?php

use Redberry\LaravelCloudSdk\Data\Metrics\MetricsMetaData;
use Redberry\LaravelCloudSdk\Enums\MetricPeriod;

it('can be created from API response data', function () {
    $data = MetricsMetaData::fromResponse([
        'period' => '24h',
        'available_periods' => ['6h', '24h', '3d', '7d', '30d'],
    ]);

    expect($data)->toBeInstanceOf(MetricsMetaData::class);
    expect($data->period)->toBe(MetricPeriod::TwentyFourHours);
    expect($data->availablePeriods)->toBe(['6h', '24h', '3d', '7d', '30d']);
});

it('casts period string to MetricPeriod enum', function () {
    $data = MetricsMetaData::fromResponse([
        'period' => '7d',
        'available_periods' => ['7d'],
    ]);

    expect($data->period)->toBeInstanceOf(MetricPeriod::class);
    expect($data->period)->toBe(MetricPeriod::SevenDays);
});

it('preserves unknown period as string', function () {
    $data = MetricsMetaData::fromResponse([
        'period' => '1y',
        'available_periods' => ['1y'],
    ]);

    expect($data->period)->toBe('1y');
});
