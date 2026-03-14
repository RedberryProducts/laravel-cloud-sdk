<?php

use App\Data\LaravelCloud\DatabaseClusters\AwsRdsConfigData;
use App\Enums\LaravelCloud\DeploymentOption;

it('can be constructed with all parameters', function () {
    $data = new AwsRdsConfigData(
        size: 'db.t3.micro',
        storage: 20,
        isPublic: false,
        usesPitr: true,
        retentionDays: 7,
        deploymentOption: DeploymentOption::SINGLE_AZ,
        maintenanceWindow: 'sun:03:00-sun:04:00',
        readReplicas: 2,
    );

    expect($data->size)->toBe('db.t3.micro');
    expect($data->storage)->toBe(20);
    expect($data->isPublic)->toBeFalse();
    expect($data->usesPitr)->toBeTrue();
    expect($data->retentionDays)->toBe(7);
    expect($data->deploymentOption)->toBe(DeploymentOption::SINGLE_AZ);
    expect($data->maintenanceWindow)->toBe('sun:03:00-sun:04:00');
    expect($data->readReplicas)->toBe(2);
});

it('can be created from API response data', function () {
    $responseData = [
        'size' => 'db.t3.medium',
        'storage' => 100,
        'is_public' => true,
        'uses_pitr' => false,
        'retention_days' => 30,
        'deployment_option' => 'multi-az',
        'maintenance_window' => 'mon:02:00-mon:03:00',
        'read_replicas' => 3,
    ];

    $data = AwsRdsConfigData::fromResponse($responseData);

    expect($data)->toBeInstanceOf(AwsRdsConfigData::class);
    expect($data->size)->toBe('db.t3.medium');
    expect($data->storage)->toBe(100);
    expect($data->isPublic)->toBeTrue();
    expect($data->usesPitr)->toBeFalse();
    expect($data->retentionDays)->toBe(30);
    expect($data->deploymentOption)->toBe(DeploymentOption::MULTI_AZ);
    expect($data->maintenanceWindow)->toBe('mon:02:00-mon:03:00');
    expect($data->readReplicas)->toBe(3);
});

it('handles null maintenance window and read replicas', function () {
    $responseData = [
        'size' => 'db.t3.micro',
        'storage' => 20,
        'is_public' => false,
        'uses_pitr' => true,
        'retention_days' => 7,
        'deployment_option' => 'single-az',
    ];

    $data = AwsRdsConfigData::fromResponse($responseData);

    expect($data->maintenanceWindow)->toBeNull();
    expect($data->readReplicas)->toBeNull();
});

it('serializes to snake_case array', function () {
    $data = new AwsRdsConfigData(
        size: 'db.t3.micro',
        storage: 20,
        isPublic: false,
        usesPitr: true,
        retentionDays: 7,
        deploymentOption: DeploymentOption::SINGLE_AZ,
        maintenanceWindow: null,
        readReplicas: null,
    );

    $array = $data->toArray();

    expect($array)->toHaveKeys(['size', 'storage', 'is_public', 'uses_pitr', 'retention_days', 'deployment_option', 'maintenance_window', 'read_replicas']);
    expect($array['deployment_option'])->toBe('single-az');
});
