<?php

use Redberry\LaravelCloudSdk\Data\Metrics\StandardMetricData;
use Redberry\LaravelCloudSdk\Data\Metrics\StandardMetricPointData;

it('can be created from API response data', function () {
    $data = StandardMetricData::fromResponse([
        'labels' => ['instance-1', 'instance-2'],
        'average' => [45.5, 30.2],
        'data' => [
            ['x' => '2024-01-01T00:00:00Z', 'y' => [50.0, 35.0]],
            ['x' => '2024-01-01T01:00:00Z', 'y' => [41.0, 25.4]],
        ],
    ]);

    expect($data)->toBeInstanceOf(StandardMetricData::class);
    expect($data->labels)->toBe(['instance-1', 'instance-2']);
    expect($data->average)->toBe([45.5, 30.2]);
    expect($data->data)->toHaveCount(2);
    expect($data->data[0])->toBeInstanceOf(StandardMetricPointData::class);
    expect($data->data[0]->x)->toBe('2024-01-01T00:00:00Z');
    expect($data->data[0]->y)->toBe([50.0, 35.0]);
});

it('handles empty data arrays', function () {
    $data = StandardMetricData::fromResponse([
        'labels' => [],
        'average' => [],
        'data' => [],
    ]);

    expect($data->labels)->toBe([]);
    expect($data->average)->toBe([]);
    expect($data->data)->toBe([]);
});
