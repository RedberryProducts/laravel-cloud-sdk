<?php

use Redberry\LaravelCloudSdk\Data\Instances\BackgroundProcessConfigData;
use Redberry\LaravelCloudSdk\Data\Instances\BackgroundProcessData;
use Redberry\LaravelCloudSdk\Enums\DaemonType;

it('builds from response attributes without config', function () {
    $data = BackgroundProcessData::fromResponse([
        'type' => 'worker',
        'processes' => 2,
    ], 'bp-123');

    expect($data->id)->toBe('bp-123');
    expect($data->type)->toBe(DaemonType::WORKER);
    expect($data->processes)->toBe(2);
    expect($data->command)->toBeNull();
    expect($data->config)->toBeNull();
});

it('builds from response attributes with command', function () {
    $data = BackgroundProcessData::fromResponse([
        'type' => 'custom',
        'processes' => 1,
        'command' => 'php artisan horizon',
    ], 'bp-456');

    expect($data->type)->toBe(DaemonType::CUSTOM);
    expect($data->command)->toBe('php artisan horizon');
});

it('builds from response attributes with config', function () {
    $data = BackgroundProcessData::fromResponse([
        'type' => 'worker',
        'processes' => 3,
        'config' => [
            'connection' => 'redis',
            'queue' => 'default',
            'tries' => 3,
        ],
    ], 'bp-789');

    expect($data->config)->toBeInstanceOf(BackgroundProcessConfigData::class);
    expect($data->config->connection)->toBe('redis');
    expect($data->config->queue)->toBe('default');
    expect($data->config->tries)->toBe(3);
});

it('serializes type and processes for a request without id', function () {
    $data = new BackgroundProcessData(
        type: DaemonType::CUSTOM,
        processes: 1,
        command: 'php artisan queue:work',
    );

    $array = $data->toArray();

    expect($array['type'])->toBe('custom');
    expect($array['processes'])->toBe(1);
    expect($array['command'])->toBe('php artisan queue:work');
    expect($array)->not->toHaveKey('id');
});

it('includes id when set from response', function () {
    $data = BackgroundProcessData::fromResponse([
        'type' => 'worker',
        'processes' => 2,
    ], 'bp-1');

    $array = $data->toArray();

    expect($array['id'])->toBe('bp-1');
});

it('excludes unset optional fields', function () {
    $data = new BackgroundProcessData(
        type: DaemonType::CUSTOM,
        processes: 1,
    );

    $array = $data->toArray();

    expect($array)->not->toHaveKey('command');
    expect($array)->not->toHaveKey('config');
    expect($array)->not->toHaveKey('id');
});
