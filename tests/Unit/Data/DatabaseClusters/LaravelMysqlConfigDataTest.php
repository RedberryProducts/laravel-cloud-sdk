<?php

use Redberry\LaravelCloudSdk\Data\DatabaseClusters\LaravelMysqlConfigData;
use Redberry\LaravelCloudSdk\Enums\DatabaseClusterSize;

it('can be constructed with all parameters', function () {
    $data = new LaravelMysqlConfigData(
        size: DatabaseClusterSize::FLEX_1VCPU_512MB,
        storage: 10,
        isPublic: false,
        usesScheduledSnapshots: true,
        retentionDays: 7,
        maintenanceWindow: 'sun:03:00-sun:04:00',
    );

    expect($data->size)->toBe(DatabaseClusterSize::FLEX_1VCPU_512MB);
    expect($data->storage)->toBe(10);
    expect($data->isPublic)->toBeFalse();
    expect($data->usesScheduledSnapshots)->toBeTrue();
    expect($data->retentionDays)->toBe(7);
    expect($data->maintenanceWindow)->toBe('sun:03:00-sun:04:00');
});

it('can be created from API response data', function () {
    $responseData = [
        'size' => 'db-flex.m-1vcpu-1gb',
        'storage' => 50,
        'is_public' => true,
        'uses_scheduled_snapshots' => false,
        'retention_days' => 14,
        'maintenance_window' => 'mon:02:00-mon:03:00',
    ];

    $data = LaravelMysqlConfigData::fromResponse($responseData);

    expect($data)->toBeInstanceOf(LaravelMysqlConfigData::class);
    expect($data->size)->toBe(DatabaseClusterSize::FLEX_1VCPU_1GB);
    expect($data->storage)->toBe(50);
    expect($data->isPublic)->toBeTrue();
    expect($data->usesScheduledSnapshots)->toBeFalse();
    expect($data->retentionDays)->toBe(14);
    expect($data->maintenanceWindow)->toBe('mon:02:00-mon:03:00');
});

it('falls back to string for unknown size values', function () {
    $responseData = [
        'size' => 'unknown-size',
        'storage' => 10,
        'is_public' => false,
        'uses_scheduled_snapshots' => true,
        'retention_days' => 7,
    ];

    $data = LaravelMysqlConfigData::fromResponse($responseData);

    expect($data->size)->toBe('unknown-size');
});

it('handles null maintenance window', function () {
    $responseData = [
        'size' => 'db-flex.m-1vcpu-512mb',
        'storage' => 10,
        'is_public' => false,
        'uses_scheduled_snapshots' => true,
        'retention_days' => 7,
    ];

    $data = LaravelMysqlConfigData::fromResponse($responseData);

    expect($data->maintenanceWindow)->toBeNull();
});

it('serializes to snake_case array', function () {
    $data = new LaravelMysqlConfigData(
        size: DatabaseClusterSize::FLEX_1VCPU_512MB,
        storage: 10,
        isPublic: false,
        usesScheduledSnapshots: true,
        retentionDays: 7,
        maintenanceWindow: null,
    );

    $array = $data->toArray();

    expect($array)->toHaveKeys(['size', 'storage', 'is_public', 'uses_scheduled_snapshots', 'retention_days', 'maintenance_window']);
    expect($array['size'])->toBe('db-flex.m-1vcpu-512mb');
});
