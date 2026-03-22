<?php

use App\Data\LaravelCloud\Instances\BackgroundProcessConfigData;
use App\Data\LaravelCloud\Instances\BackgroundProcessData;
use App\Enums\LaravelCloud\DaemonType;
use Spatie\LaravelData\Optional;

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

it('serializes type as snake_case value', function () {
    $data = new BackgroundProcessData(
        id: 'bp-1',
        type: DaemonType::WORKER,
        processes: 2,
    );

    $array = $data->toArray();

    expect($array['id'])->toBe('bp-1');
    expect($array['type'])->toBe('worker');
    expect($array['processes'])->toBe(2);
});

it('excludes unset optional fields', function () {
    $data = new BackgroundProcessData(
        id: 'bp-1',
        type: DaemonType::CUSTOM,
        processes: 1,
    );

    $array = $data->toArray();

    expect($array)->not->toHaveKey('command');
    expect($array)->not->toHaveKey('config');
});
