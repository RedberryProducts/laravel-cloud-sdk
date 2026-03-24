<?php

use App\Data\LaravelCloud\DatabaseClusters\CreateDatabaseClusterData;
use App\Data\LaravelCloud\DatabaseClusters\NeonConfigData;
use App\Enums\LaravelCloud\CloudRegion;
use App\Enums\LaravelCloud\DatabaseType;

use Spatie\LaravelData\Optional;

it('can be constructed with all parameters', function () {
    $config = new NeonConfigData(
        cuMin: 0.25,
        cuMax: 2.0,
        suspendSeconds: 300,
        retentionDays: 7,
    );

    $data = new CreateDatabaseClusterData(
        name: 'test-cluster',
        type: DatabaseType::NEON_SERVERLESS_POSTGRES_17,
        region: CloudRegion::US_EAST_1,
        config: $config,
        clusterId: 42,
    );

    expect($data->name)->toBe('test-cluster');
    expect($data->type)->toBe(DatabaseType::NEON_SERVERLESS_POSTGRES_17);
    expect($data->region)->toBe(CloudRegion::US_EAST_1);
    expect($data->config)->toBeInstanceOf(NeonConfigData::class);
    expect($data->clusterId)->toBe(42);
});

it('defaults clusterId to Optional', function () {
    $config = new NeonConfigData(
        cuMin: 0.25,
        cuMax: 0.25,
        suspendSeconds: 300,
        retentionDays: 7,
    );

    $data = new CreateDatabaseClusterData(
        name: 'test-cluster',
        type: DatabaseType::NEON_SERVERLESS_POSTGRES_17,
        region: CloudRegion::US_EAST_1,
        config: $config,
    );

    expect($data->clusterId)->toBeInstanceOf(Optional::class);
});
