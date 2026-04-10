<?php

use Carbon\CarbonImmutable;
use Redberry\LaravelCloudSdk\Data\DedicatedClusters\DedicatedClusterData;
use Redberry\LaravelCloudSdk\Enums\CloudRegion;
use Redberry\LaravelCloudSdk\Enums\ClusterStatus;
use Redberry\LaravelCloudSdk\Enums\ClusterType;
use Redberry\LaravelCloudSdk\Enums\TenancyType;

it('can be created from API response data', function () {
    $responseData = [
        'name' => 'my-cluster',
        'region' => 'us-east-1',
        'type' => 'applications',
        'tenancy_type' => 'dedicated',
        'status' => 'active',
        'created_at' => '2024-06-15T10:30:00Z',
    ];

    $data = DedicatedClusterData::fromResponse($responseData, 'cluster-123');

    expect($data)->toBeInstanceOf(DedicatedClusterData::class);
    expect($data->id)->toBe('cluster-123');
    expect($data->name)->toBe('my-cluster');
    expect($data->region)->toBe(CloudRegion::UsEast1);
    expect($data->type)->toBe(ClusterType::Applications);
    expect($data->tenancyType)->toBe(TenancyType::Dedicated);
    expect($data->status)->toBe(ClusterStatus::Active);
    expect($data->createdAt)->toBeInstanceOf(CarbonImmutable::class);
});

it('handles null created_at', function () {
    $responseData = [
        'name' => 'draft-cluster',
        'region' => 'eu-central-1',
        'type' => 'mysql-databases',
        'tenancy_type' => 'shared',
        'status' => 'draft',
    ];

    $data = DedicatedClusterData::fromResponse($responseData, 'cluster-456');

    expect($data->createdAt)->toBeNull();
    expect($data->type)->toBe(ClusterType::MysqlDatabases);
    expect($data->tenancyType)->toBe(TenancyType::Shared);
    expect($data->status)->toBe(ClusterStatus::Draft);
});

it('keeps unknown enum values as strings', function () {
    $responseData = [
        'name' => 'unknown-cluster',
        'region' => 'unknown-region',
        'type' => 'unknown-type',
        'tenancy_type' => 'unknown-tenancy',
        'status' => 'unknown-status',
        'created_at' => '2024-06-15T10:30:00Z',
    ];

    $data = DedicatedClusterData::fromResponse($responseData, 'cluster-789');

    expect($data->region)->toBe('unknown-region');
    expect($data->type)->toBe('unknown-type');
    expect($data->tenancyType)->toBe('unknown-tenancy');
    expect($data->status)->toBe('unknown-status');
});
