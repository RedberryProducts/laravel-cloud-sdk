<?php

use Redberry\LaravelCloudSdk\Data\BackgroundProcesses\BackgroundProcessConfigData;
use Redberry\LaravelCloudSdk\Data\BackgroundProcesses\UpdateBackgroundProcessData;
use Redberry\LaravelCloudSdk\Enums\DaemonType;
use Spatie\LaravelData\Optional;

it('defaults all parameters to Optional', function () {
    $data = new UpdateBackgroundProcessData;

    expect($data->type)->toBeInstanceOf(Optional::class);
    expect($data->processes)->toBeInstanceOf(Optional::class);
    expect($data->command)->toBeInstanceOf(Optional::class);
    expect($data->config)->toBeInstanceOf(Optional::class);
});

it('serializes set fields and excludes unset optionals', function () {
    $data = new UpdateBackgroundProcessData(
        type: DaemonType::Custom,
        processes: 3,
        command: 'php artisan my:command',
    );

    $array = $data->toArray();

    expect($array['type'])->toBe(DaemonType::Custom->value);
    expect($array['processes'])->toBe(3);
    expect($array['command'])->toBe('php artisan my:command');
    expect($array)->not->toHaveKey('config');
});

it('serializes type as enum value', function () {
    $data = new UpdateBackgroundProcessData(type: DaemonType::Worker);

    $array = $data->toArray();

    expect($array['type'])->toBe(DaemonType::Worker->value);
});

it('excludes unset optional fields', function () {
    $data = new UpdateBackgroundProcessData(processes: 3);

    $array = $data->toArray();

    expect($array)->toHaveKey('processes', 3);
    expect($array)->not->toHaveKey('type');
    expect($array)->not->toHaveKey('command');
    expect($array)->not->toHaveKey('config');
});

it('serializes nested config when set', function () {
    $config = new BackgroundProcessConfigData(
        connection: 'redis',
        queue: 'emails',
        timeout: 60,
    );

    $data = new UpdateBackgroundProcessData(config: $config);

    $array = $data->toArray();

    expect($array['config']['connection'])->toBe('redis');
    expect($array['config']['queue'])->toBe('emails');
    expect($array['config']['timeout'])->toBe(60);
    expect($array['config'])->not->toHaveKey('tries');
});
