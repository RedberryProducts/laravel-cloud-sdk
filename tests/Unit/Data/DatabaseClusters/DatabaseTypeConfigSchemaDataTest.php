<?php

use App\Data\LaravelCloud\DatabaseClusters\DatabaseTypeConfigSchemaData;

it('can be created from API response data with all fields', function () {
    $responseData = [
        'name' => 'size',
        'type' => 'string',
        'required' => true,
        'description' => 'Instance size for the database cluster',
        'enum' => ['db-flex.m-1vcpu-512mb', 'db-flex.m-1vcpu-1gb'],
        'min' => 1,
        'max' => 100,
        'nullable' => true,
        'example' => 'db-flex.m-1vcpu-512mb',
    ];

    $data = DatabaseTypeConfigSchemaData::fromResponse($responseData);

    expect($data)->toBeInstanceOf(DatabaseTypeConfigSchemaData::class);
    expect($data->name)->toBe('size');
    expect($data->type)->toBe('string');
    expect($data->required)->toBeTrue();
    expect($data->description)->toBe('Instance size for the database cluster');
    expect($data->enum)->toBe(['db-flex.m-1vcpu-512mb', 'db-flex.m-1vcpu-1gb']);
    expect($data->min)->toBe(1);
    expect($data->max)->toBe(100);
    expect($data->nullable)->toBeTrue();
    expect($data->example)->toBe('db-flex.m-1vcpu-512mb');
});

it('can be created from API response data with only required fields', function () {
    $responseData = [
        'name' => 'is_public',
        'type' => 'boolean',
        'required' => true,
        'description' => 'Whether the database is publicly accessible',
    ];

    $data = DatabaseTypeConfigSchemaData::fromResponse($responseData);

    expect($data->name)->toBe('is_public');
    expect($data->type)->toBe('boolean');
    expect($data->required)->toBeTrue();
    expect($data->description)->toBe('Whether the database is publicly accessible');
    expect($data->enum)->toBeNull();
    expect($data->min)->toBeNull();
    expect($data->max)->toBeNull();
    expect($data->nullable)->toBeNull();
    expect($data->example)->toBeNull();
});
