<?php

use Redberry\LaravelCloudSdk\Data\Environments\EnvironmentLogEntryData;
use Redberry\LaravelCloudSdk\Enums\LogEntryType;
use Redberry\LaravelCloudSdk\Enums\LogLevel;

it('can be created from API response data', function () {
    $data = EnvironmentLogEntryData::fromResponse([
        'message' => 'GET /api/health 200',
        'level' => 'info',
        'type' => 'access',
        'logged_at' => '2026-04-09T12:00:00Z',
        'data' => ['method' => 'GET', 'path' => '/api/health'],
    ]);

    expect($data)->toBeInstanceOf(EnvironmentLogEntryData::class);
    expect($data->message)->toBe('GET /api/health 200');
    expect($data->level)->toBe(LogLevel::Info);
    expect($data->type)->toBe(LogEntryType::Access);
    expect($data->loggedAt)->toBe('2026-04-09T12:00:00Z');
    expect($data->data)->toBe(['method' => 'GET', 'path' => '/api/health']);
});

it('casts level and type to enums', function () {
    $data = EnvironmentLogEntryData::fromResponse([
        'message' => 'Something went wrong',
        'level' => 'error',
        'type' => 'exception',
        'logged_at' => '2026-04-09T12:00:00Z',
        'data' => null,
    ]);

    expect($data->level)->toBeInstanceOf(LogLevel::class);
    expect($data->level)->toBe(LogLevel::Error);
    expect($data->type)->toBeInstanceOf(LogEntryType::class);
    expect($data->type)->toBe(LogEntryType::Exception);
    expect($data->data)->toBeNull();
});

it('preserves unknown level and type as strings', function () {
    $data = EnvironmentLogEntryData::fromResponse([
        'message' => 'test',
        'level' => 'trace',
        'type' => 'custom',
        'logged_at' => '2026-04-09T12:00:00Z',
    ]);

    expect($data->level)->toBe('trace');
    expect($data->type)->toBe('custom');
    expect($data->data)->toBeNull();
});
