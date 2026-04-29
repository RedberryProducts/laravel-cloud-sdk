<?php

use Redberry\LaravelCloudSdk\Data\DatabaseClusters\AwsRdsConfigData;
use Redberry\LaravelCloudSdk\Data\DatabaseClusters\CreateDatabaseClusterData;
use Redberry\LaravelCloudSdk\Data\DatabaseClusters\LaravelMysqlConfigData;
use Redberry\LaravelCloudSdk\Data\DatabaseClusters\NeonServerlessPostgresConfigData;
use Redberry\LaravelCloudSdk\Enums\CloudRegion;
use Redberry\LaravelCloudSdk\Enums\DatabaseClusterSize;
use Redberry\LaravelCloudSdk\Enums\DatabaseType;
use Redberry\LaravelCloudSdk\Enums\DeploymentOption;
use Redberry\LaravelCloudSdk\Enums\NeonServerlessPostgresComputeUnit;
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

it('round-trips with NeonServerlessPostgres config', function () {
    $original = new CreateDatabaseClusterData(
        name: 'primary-db',
        type: DatabaseType::NeonServerlessPostgres18,
        region: CloudRegion::UsEast1,
        config: new NeonServerlessPostgresConfigData(
            cuMin: NeonServerlessPostgresComputeUnit::Cu0_25,
            cuMax: NeonServerlessPostgresComputeUnit::Cu1,
            suspendSeconds: 60,
            retentionDays: 7,
        ),
    );

    $reconstructed = CreateDatabaseClusterData::from($original->toArray());

    expect($reconstructed->config)->toBeInstanceOf(NeonServerlessPostgresConfigData::class)
        ->and($reconstructed->config->suspendSeconds)->toBe(60)
        ->and($reconstructed->config->retentionDays)->toBe(7);
});

it('round-trips with LaravelMysql config', function () {
    $original = new CreateDatabaseClusterData(
        name: 'primary-db',
        type: DatabaseType::LaravelMysql84,
        region: CloudRegion::UsEast1,
        config: new LaravelMysqlConfigData(
            size: DatabaseClusterSize::Flex1vcpu1gb,
            storage: 50,
            isPublic: false,
            usesScheduledSnapshots: true,
            retentionDays: 14,
            maintenanceWindow: 'sun:03:00-sun:04:00',
        ),
    );

    $reconstructed = CreateDatabaseClusterData::from($original->toArray());

    expect($reconstructed->config)->toBeInstanceOf(LaravelMysqlConfigData::class)
        ->and($reconstructed->config->storage)->toBe(50)
        ->and($reconstructed->config->usesScheduledSnapshots)->toBeTrue()
        ->and($reconstructed->config->maintenanceWindow)->toBe('sun:03:00-sun:04:00');
});

it('round-trips with AwsRds config', function () {
    $original = new CreateDatabaseClusterData(
        name: 'primary-db',
        type: DatabaseType::AwsRdsPostgres18,
        region: CloudRegion::EuCentral1,
        config: new AwsRdsConfigData(
            size: DatabaseClusterSize::Flex1vcpu1gb,
            storage: 100,
            isPublic: false,
            usesPitr: true,
            retentionDays: 30,
            deploymentOption: DeploymentOption::SingleAz,
            maintenanceWindow: null,
            readReplicas: 1,
        ),
    );

    $reconstructed = CreateDatabaseClusterData::from($original->toArray());

    expect($reconstructed->config)->toBeInstanceOf(AwsRdsConfigData::class)
        ->and($reconstructed->config->storage)->toBe(100)
        ->and($reconstructed->config->usesPitr)->toBeTrue()
        ->and($reconstructed->config->deploymentOption)->toBe(DeploymentOption::SingleAz);
});

it('preserves unknown database types as raw arrays via fromArray', function () {
    $rawConfig = ['size' => 'mystery.size', 'storage' => 999, 'unknown_field' => 'whatever'];

    $cluster = CreateDatabaseClusterData::fromArray([
        'name' => 'experimental-db',
        'type' => 'mystery_db_99',
        'region' => 'us-east-1',
        'config' => $rawConfig,
    ]);

    expect($cluster->config)->toBe($rawConfig)
        ->and($cluster->type)->toBe('mystery_db_99');
});
