<?php

use App\Data\LaravelCloud\DatabaseClusters\AwsRdsConfigData;
use App\Data\LaravelCloud\DatabaseClusters\DatabaseClusterData;
use App\Data\LaravelCloud\DatabaseClusters\DatabaseConnectionData;
use App\Data\LaravelCloud\DatabaseClusters\LaravelMysqlConfigData;
use App\Data\LaravelCloud\DatabaseClusters\NeonConfigData;
use App\Enums\LaravelCloud\CloudRegion;
use App\Enums\LaravelCloud\DatabaseStatus;
use App\Enums\LaravelCloud\DatabaseType;
use Carbon\CarbonImmutable;

it('can be created from neon response data', function () {
    $responseData = [
        'name' => 'my-neon-db',
        'type' => 'neon_serverless_postgres_17',
        'status' => 'available',
        'region' => 'us-east-1',
        'config' => [
            'cu_min' => 0.25,
            'cu_max' => 2.0,
            'suspend_seconds' => 300,
            'retention_days' => 7,
        ],
        'connection' => [
            'hostname' => 'neon-db.example.com',
            'port' => 5432,
            'protocol' => 'postgres',
            'driver' => 'pgsql',
            'username' => 'admin',
            'password' => 'secret',
        ],
        'created_at' => '2024-06-15T10:30:00Z',
    ];

    $data = DatabaseClusterData::fromResponse($responseData, 'cluster-123');

    expect($data)->toBeInstanceOf(DatabaseClusterData::class);
    expect($data->id)->toBe('cluster-123');
    expect($data->name)->toBe('my-neon-db');
    expect($data->type)->toBe(DatabaseType::NEON_SERVERLESS_POSTGRES_17);
    expect($data->status)->toBe(DatabaseStatus::AVAILABLE);
    expect($data->region)->toBe(CloudRegion::US_EAST_1);
    expect($data->config)->toBeInstanceOf(NeonConfigData::class);
    expect($data->config->cuMin)->toBe(0.25);
    expect($data->config->cuMax)->toBe(2.0);
    expect($data->connection)->toBeInstanceOf(DatabaseConnectionData::class);
    expect($data->connection->hostname)->toBe('neon-db.example.com');
    expect($data->createdAt)->toBeInstanceOf(CarbonImmutable::class);
});

it('can be created from laravel mysql response data', function () {
    $responseData = [
        'name' => 'my-mysql-db',
        'type' => 'laravel_mysql_84',
        'status' => 'creating',
        'region' => 'eu-central-1',
        'config' => [
            'size' => 'small',
            'storage' => 10,
            'is_public' => false,
            'uses_scheduled_snapshots' => true,
            'retention_days' => 7,
            'maintenance_window' => 'sun:03:00-sun:04:00',
        ],
        'connection' => [
            'hostname' => 'mysql-db.example.com',
            'port' => 3306,
            'protocol' => 'mysql',
            'driver' => 'mysql',
            'username' => 'root',
            'password' => 'password',
        ],
        'created_at' => '2024-06-15T10:30:00Z',
    ];

    $data = DatabaseClusterData::fromResponse($responseData, 'cluster-456');

    expect($data->config)->toBeInstanceOf(LaravelMysqlConfigData::class);
    expect($data->config->size)->toBe('small');
    expect($data->config->usesScheduledSnapshots)->toBeTrue();
});

it('can be created from aws rds response data', function () {
    $responseData = [
        'name' => 'my-rds-db',
        'type' => 'aws_rds_mysql_8',
        'status' => 'available',
        'region' => 'us-east-2',
        'config' => [
            'size' => 'db.t3.micro',
            'storage' => 20,
            'is_public' => false,
            'uses_pitr' => true,
            'retention_days' => 7,
            'deployment_option' => 'single-az',
            'maintenance_window' => 'sun:03:00-sun:04:00',
            'read_replicas' => 0,
        ],
        'connection' => [
            'hostname' => 'rds-db.example.com',
            'port' => 3306,
            'protocol' => 'mysql',
            'driver' => 'mysql',
            'username' => 'admin',
            'password' => 'secret',
        ],
        'created_at' => '2024-06-15T10:30:00Z',
    ];

    $data = DatabaseClusterData::fromResponse($responseData, 'cluster-789');

    expect($data->config)->toBeInstanceOf(AwsRdsConfigData::class);
    expect($data->config->size)->toBe('db.t3.micro');
    expect($data->config->usesPitr)->toBeTrue();
});

it('handles null created_at', function () {
    $responseData = [
        'name' => 'my-db',
        'type' => 'neon_serverless_postgres_17',
        'status' => 'creating',
        'region' => 'us-east-1',
        'config' => [
            'cu_min' => 0.25,
            'cu_max' => 0.25,
            'suspend_seconds' => 300,
            'retention_days' => 7,
        ],
        'connection' => [
            'hostname' => 'db.example.com',
            'port' => 5432,
            'protocol' => 'postgres',
            'driver' => 'pgsql',
            'username' => 'admin',
            'password' => 'secret',
        ],
    ];

    $data = DatabaseClusterData::fromResponse($responseData, 'cluster-123');

    expect($data->createdAt)->toBeNull();
});
