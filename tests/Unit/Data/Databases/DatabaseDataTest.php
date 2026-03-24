<?php

use Redberry\LaravelCloudSdk\Data\Databases\DatabaseData;
use Carbon\CarbonImmutable;

it('can be created from API response data', function () {
    $data = DatabaseData::fromResponse([
        'name' => 'my-database',
        'created_at' => '2024-06-15T10:30:00Z',
    ], 'db-123');

    expect($data)->toBeInstanceOf(DatabaseData::class);
    expect($data->id)->toBe('db-123');
    expect($data->name)->toBe('my-database');
    expect($data->createdAt)->toBeInstanceOf(CarbonImmutable::class);
});

it('handles null created_at', function () {
    $data = DatabaseData::fromResponse([
        'name' => 'my-database',
        'created_at' => null,
    ], 'db-456');

    expect($data->createdAt)->toBeNull();
});
