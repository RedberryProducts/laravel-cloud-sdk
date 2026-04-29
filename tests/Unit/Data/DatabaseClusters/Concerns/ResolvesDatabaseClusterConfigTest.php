<?php

use Redberry\LaravelCloudSdk\Data\DatabaseClusters\AwsRdsConfigData;
use Redberry\LaravelCloudSdk\Data\DatabaseClusters\Concerns\ResolvesDatabaseClusterConfig;
use Redberry\LaravelCloudSdk\Data\DatabaseClusters\LaravelMysqlConfigData;
use Redberry\LaravelCloudSdk\Data\DatabaseClusters\NeonServerlessPostgresConfigData;
use Redberry\LaravelCloudSdk\Enums\DatabaseType;

/**
 * Test fixture exposing the trait's private static method as public so it
 * can be exercised in isolation.
 */
class ResolvesDatabaseClusterConfigTester
{
    use ResolvesDatabaseClusterConfig {
        resolveConfig as public exposeResolveConfig;
    }
}

it('resolves NeonServerlessPostgres types to NeonServerlessPostgresConfigData', function (DatabaseType $type) {
    $config = [
        'cu_min' => '0.25',
        'cu_max' => '1',
        'suspend_seconds' => 60,
        'retention_days' => 7,
    ];

    $resolved = ResolvesDatabaseClusterConfigTester::exposeResolveConfig($type->value, $config);

    expect($resolved)->toBeInstanceOf(NeonServerlessPostgresConfigData::class)
        ->and($resolved->suspendSeconds)->toBe(60);
})->with([
    'v18' => DatabaseType::NeonServerlessPostgres18,
    'v17' => DatabaseType::NeonServerlessPostgres17,
    'v16' => DatabaseType::NeonServerlessPostgres16,
]);

it('resolves LaravelMysql types to LaravelMysqlConfigData', function (DatabaseType $type) {
    $config = [
        'size' => 'db-flex.m-1vcpu-1gb',
        'storage' => 50,
        'is_public' => false,
        'uses_scheduled_snapshots' => true,
        'retention_days' => 7,
        'maintenance_window' => null,
    ];

    $resolved = ResolvesDatabaseClusterConfigTester::exposeResolveConfig($type->value, $config);

    expect($resolved)->toBeInstanceOf(LaravelMysqlConfigData::class)
        ->and($resolved->storage)->toBe(50);
})->with([
    'v8.4' => DatabaseType::LaravelMysql84,
    'v8' => DatabaseType::LaravelMysql8,
]);

it('resolves AWS RDS types to AwsRdsConfigData', function (DatabaseType $type) {
    $config = [
        'size' => 'db.m8g.large',
        'storage' => 100,
        'is_public' => false,
        'uses_pitr' => true,
        'retention_days' => 14,
        'deployment_option' => 'single-az',
        'maintenance_window' => null,
        'read_replicas' => 0,
    ];

    $resolved = ResolvesDatabaseClusterConfigTester::exposeResolveConfig($type->value, $config);

    expect($resolved)->toBeInstanceOf(AwsRdsConfigData::class)
        ->and($resolved->storage)->toBe(100);
})->with([
    'mysql8' => DatabaseType::AwsRdsMysql8,
    'postgres18' => DatabaseType::AwsRdsPostgres18,
]);

it('falls back to the raw array for unknown database types', function () {
    $config = ['size' => 'mystery.size', 'unknown_field' => 'value'];

    $resolved = ResolvesDatabaseClusterConfigTester::exposeResolveConfig('mystery_db_99', $config);

    expect($resolved)->toBe($config);
});

it('accepts the type as either a DatabaseType enum or its string value', function () {
    $config = [
        'cu_min' => '0.25',
        'cu_max' => '1',
        'suspend_seconds' => 60,
        'retention_days' => 7,
    ];

    $fromEnum = ResolvesDatabaseClusterConfigTester::exposeResolveConfig(DatabaseType::NeonServerlessPostgres18, $config);
    $fromString = ResolvesDatabaseClusterConfigTester::exposeResolveConfig(DatabaseType::NeonServerlessPostgres18->value, $config);

    expect($fromEnum)->toBeInstanceOf(NeonServerlessPostgresConfigData::class)
        ->and($fromString)->toBeInstanceOf(NeonServerlessPostgresConfigData::class);
});
