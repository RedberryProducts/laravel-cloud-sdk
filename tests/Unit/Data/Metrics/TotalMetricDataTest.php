<?php

use Redberry\LaravelCloudSdk\Data\Metrics\TotalMetricData;
use Redberry\LaravelCloudSdk\Data\Metrics\TotalMetricPointData;

it('can be created from API response data', function () {
    $data = TotalMetricData::fromResponse([
        'data' => [
            ['x' => '2024-01-01T00:00:00Z', 'y' => 1024.5],
            ['x' => '2024-01-01T01:00:00Z', 'y' => 2048.0],
        ],
        'total' => 3072.5,
    ]);

    expect($data)->toBeInstanceOf(TotalMetricData::class);
    expect($data->total)->toBe(3072.5);
    expect($data->data)->toHaveCount(2);
    expect($data->data[0])->toBeInstanceOf(TotalMetricPointData::class);
    expect($data->data[0]->x)->toBe('2024-01-01T00:00:00Z');
    expect($data->data[0]->y)->toBe(1024.5);
});

it('handles empty data arrays', function () {
    $data = TotalMetricData::fromResponse([
        'data' => [],
        'total' => 0.0,
    ]);

    expect($data->data)->toBe([]);
    expect($data->total)->toBe(0.0);
});
