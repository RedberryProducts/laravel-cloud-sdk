<?php

use Redberry\LaravelCloudSdk\Data\DatabaseClusters\NeonServerlessPostgresConfigData;
use Redberry\LaravelCloudSdk\Enums\NeonServerlessPostgresComputeUnit;

it('can be constructed with all parameters', function () {
    $data = new NeonServerlessPostgresConfigData(
        cuMin: NeonServerlessPostgresComputeUnit::CU_0_25,
        cuMax: NeonServerlessPostgresComputeUnit::CU_2,
        suspendSeconds: 300,
        retentionDays: 7,
    );

    expect($data->cuMin)->toBe(NeonServerlessPostgresComputeUnit::CU_0_25);
    expect($data->cuMax)->toBe(NeonServerlessPostgresComputeUnit::CU_2);
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

    $data = NeonServerlessPostgresConfigData::fromResponse($responseData);

    expect($data)->toBeInstanceOf(NeonServerlessPostgresConfigData::class);
    expect($data->cuMin)->toBe(NeonServerlessPostgresComputeUnit::CU_0_5);
    expect($data->cuMax)->toBe(NeonServerlessPostgresComputeUnit::CU_4);
    expect($data->suspendSeconds)->toBe(600);
    expect($data->retentionDays)->toBe(14);
});

it('falls back to float for unknown compute unit values', function () {
    $responseData = [
        'cu_min' => 3.0,
        'cu_max' => 6.0,
        'suspend_seconds' => 300,
        'retention_days' => 7,
    ];

    $data = NeonServerlessPostgresConfigData::fromResponse($responseData);

    expect($data->cuMin)->toBe(3.0);
    expect($data->cuMax)->toBe(6.0);
});

it('serializes to snake_case array with float values', function () {
    $data = new NeonServerlessPostgresConfigData(
        cuMin: NeonServerlessPostgresComputeUnit::CU_0_25,
        cuMax: NeonServerlessPostgresComputeUnit::CU_1,
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

it('serializes raw float values correctly', function () {
    $data = new NeonServerlessPostgresConfigData(
        cuMin: 0.5,
        cuMax: 2.0,
        suspendSeconds: 600,
        retentionDays: 14,
    );

    $array = $data->toArray();

    expect($array['cu_min'])->toBe(0.5);
    expect($array['cu_max'])->toBe(2.0);
});
