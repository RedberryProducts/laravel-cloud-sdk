<?php

use Redberry\LaravelCloudSdk\Data\DatabaseClusters\CreateDatabaseClusterData;
use Redberry\LaravelCloudSdk\Data\DatabaseClusters\NeonServerlessPostgresConfigData;
use Redberry\LaravelCloudSdk\Enums\CloudRegion;
use Redberry\LaravelCloudSdk\Enums\DatabaseType;
use Spatie\LaravelData\Optional;

it('can be constructed with all parameters', function () {
    $config = new NeonServerlessPostgresConfigData(
        cuMin: 0.25,
        cuMax: 2.0,
        suspendSeconds: 300,
        retentionDays: 7,
    );

    $data = new CreateDatabaseClusterData(
        name: 'test-cluster',
        type: DatabaseType::NeonServerlessPostgres17,
        region: CloudRegion::UsEast1,
        config: $config,
        clusterId: 42,
    );

    expect($data->name)->toBe('test-cluster');
    expect($data->type)->toBe(DatabaseType::NeonServerlessPostgres17);
    expect($data->region)->toBe(CloudRegion::UsEast1);
    expect($data->config)->toBeInstanceOf(NeonServerlessPostgresConfigData::class);
    expect($data->clusterId)->toBe(42);
});

it('defaults clusterId to Optional', function () {
    $config = new NeonServerlessPostgresConfigData(
        cuMin: 0.25,
        cuMax: 0.25,
        suspendSeconds: 300,
        retentionDays: 7,
    );

    $data = new CreateDatabaseClusterData(
        name: 'test-cluster',
        type: DatabaseType::NeonServerlessPostgres17,
        region: CloudRegion::UsEast1,
        config: $config,
    );

    expect($data->clusterId)->toBeInstanceOf(Optional::class);
});
