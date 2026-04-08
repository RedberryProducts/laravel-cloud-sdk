<?php

use Redberry\LaravelCloudSdk\Data\Metrics\MetricData;
use Redberry\LaravelCloudSdk\Data\Metrics\MetricPointData;

it('can be created from standard metric response data with labels', function () {
    $data = MetricData::fromResponse([
        'labels' => ['instance-1', 'instance-2'],
        'average' => [45.5, 30.2],
        'data' => [
            ['x' => '2024-01-01T00:00:00Z', 'y' => [50.0, 35.0]],
            ['x' => '2024-01-01T01:00:00Z', 'y' => [41.0, 25.4]],
        ],
    ]);

    expect($data)->toBeInstanceOf(MetricData::class);
    expect($data->labels)->toBe(['instance-1', 'instance-2']);
    expect($data->average)->toBe([45.5, 30.2]);
    expect($data->data)->toHaveCount(2);
    expect($data->data[0])->toBeInstanceOf(MetricPointData::class);
    expect($data->data[0]->x)->toBe('2024-01-01T00:00:00Z');
    expect($data->data[0]->y)->toBe([50.0, 35.0]);
});

it('can be created from total metric response data', function () {
    $data = MetricData::fromResponse([
        'data' => [
            ['x' => '2024-01-01T00:00:00Z', 'y' => 1024.5],
            ['x' => '2024-01-01T01:00:00Z', 'y' => 2048.0],
        ],
        'total' => 3072.5,
    ]);

    expect($data)->toBeInstanceOf(MetricData::class);
    expect($data->total)->toBe(3072.5);
    expect($data->labels)->toBeNull();
    expect($data->data)->toHaveCount(2);
    expect($data->data[0])->toBeInstanceOf(MetricPointData::class);
    expect($data->data[0]->x)->toBe('2024-01-01T00:00:00Z');
    expect($data->data[0]->y)->toBe(1024.5);
});

it('can be created from scalar metric response data with current/min/max', function () {
    $data = MetricData::fromResponse([
        'data' => [
            ['x' => '2024-01-01T00:00:00Z', 'y' => 42],
        ],
        'current' => 42,
        'min' => 10,
        'max' => 100,
        'average' => 55.5,
    ]);

    expect($data)->toBeInstanceOf(MetricData::class);
    expect($data->current)->toBe(42);
    expect($data->min)->toBe(10);
    expect($data->max)->toBe(100);
    expect($data->average)->toBe(55.5);
    expect($data->labels)->toBeNull();
    expect($data->total)->toBeNull();
});

it('handles empty data arrays', function () {
    $data = MetricData::fromResponse([
        'data' => [],
    ]);

    expect($data->data)->toBe([]);
    expect($data->labels)->toBeNull();
    expect($data->average)->toBeNull();
    expect($data->total)->toBeNull();
});
