<?php

use Redberry\LaravelCloudSdk\Data\DatabaseClusters\NeonConfigData;

it('can be constructed with all parameters', function () {
    $data = new NeonConfigData(
        cuMin: 0.25,
        cuMax: 2.0,
        suspendSeconds: 300,
        retentionDays: 7,
    );

    expect($data->cuMin)->toBe(0.25);
    expect($data->cuMax)->toBe(2.0);
    expect($data->suspendSeconds)->toBe(300);
    expect($data->retentionDays)->toBe(7);
});

it('can be created from API response data', function () {
    $responseData = [
        'cu_min' => 0.5,
        'cu_max' => 4.0,
        'suspend_seconds' => 600,
        'retention_days' => 14,
    ];

    $data = NeonConfigData::fromResponse($responseData);

    expect($data)->toBeInstanceOf(NeonConfigData::class);
    expect($data->cuMin)->toBe(0.5);
    expect($data->cuMax)->toBe(4.0);
    expect($data->suspendSeconds)->toBe(600);
    expect($data->retentionDays)->toBe(14);
});

it('serializes to snake_case array', function () {
    $data = new NeonConfigData(
        cuMin: 0.25,
        cuMax: 1.0,
        suspendSeconds: 300,
        retentionDays: 7,
    );

    $array = $data->toArray();

    expect($array)->toHaveKeys(['cu_min', 'cu_max', 'suspend_seconds', 'retention_days']);
    expect($array['cu_min'])->toBe(0.25);
    expect($array['cu_max'])->toBe(1.0);
    expect($array['suspend_seconds'])->toBe(300);
    expect($array['retention_days'])->toBe(7);
});
