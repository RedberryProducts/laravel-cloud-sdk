<?php

use Redberry\LaravelCloudSdk\Data\DatabaseClusters\DatabaseTypeConfigSchemaData;
use Redberry\LaravelCloudSdk\Data\DatabaseClusters\DatabaseTypeData;
use Redberry\LaravelCloudSdk\Enums\CloudRegion;

it('can be created from API response data', function () {
    $responseData = [
        'type' => 'neon_serverless_postgres_17',
        'label' => 'Laravel Serverless Postgres 17',
        'regions' => ['us-east-1', 'eu-central-1'],
        'config_schema' => [
            [
                'name' => 'cu_min',
                'type' => 'number',
                'required' => true,
                'description' => 'Minimum compute units for auto-scaling',
                'enum' => [0.25, 0.5, 1, 2, 4, 8, 10],
            ],
            [
                'name' => 'suspend_seconds',
                'type' => 'integer',
                'required' => true,
                'description' => 'Seconds of inactivity before hibernation',
                'min' => 0,
                'max' => 604800,
            ],
        ],
    ];

    $data = DatabaseTypeData::fromResponse($responseData);

    expect($data)->toBeInstanceOf(DatabaseTypeData::class);
    expect($data->type)->toBe('neon_serverless_postgres_17');
    expect($data->label)->toBe('Laravel Serverless Postgres 17');
    expect($data->regions)->toHaveCount(2);
    expect($data->regions[0])->toBe(CloudRegion::US_EAST_1);
    expect($data->regions[1])->toBe(CloudRegion::EU_CENTRAL_1);
    expect($data->configSchema)->toHaveCount(2);
    expect($data->configSchema[0])->toBeInstanceOf(DatabaseTypeConfigSchemaData::class);
    expect($data->configSchema[0]->name)->toBe('cu_min');
    expect($data->configSchema[0]->enum)->toBe([0.25, 0.5, 1, 2, 4, 8, 10]);
    expect($data->configSchema[1]->name)->toBe('suspend_seconds');
    expect($data->configSchema[1]->min)->toBe(0);
    expect($data->configSchema[1]->max)->toBe(604800);
});

it('handles empty regions and config schema', function () {
    $responseData = [
        'type' => 'test_db',
        'label' => 'Test Database',
        'regions' => [],
        'config_schema' => [],
    ];

    $data = DatabaseTypeData::fromResponse($responseData);

    expect($data->regions)->toBeEmpty();
    expect($data->configSchema)->toBeEmpty();
});
