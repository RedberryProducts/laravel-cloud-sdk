<?php

use Redberry\LaravelCloudSdk\Data\BackgroundProcesses\CreateBackgroundProcessData;
use Redberry\LaravelCloudSdk\Data\Instances\BackgroundProcessConfigData;
use Redberry\LaravelCloudSdk\Enums\DaemonType;
use Spatie\LaravelData\Optional;

it('can be constructed with required parameters', function () {
    $data = new CreateBackgroundProcessData(
        type: DaemonType::WORKER,
        processes: 2,
    );

    expect($data->type)->toBe(DaemonType::WORKER);
    expect($data->processes)->toBe(2);
});

it('defaults optional fields to Optional', function () {
    $data = new CreateBackgroundProcessData(
        type: DaemonType::WORKER,
        processes: 1,
    );

    expect($data->command)->toBeInstanceOf(Optional::class);
    expect($data->config)->toBeInstanceOf(Optional::class);
});

it('serializes to array correctly for custom type', function () {
    $data = new CreateBackgroundProcessData(
        type: DaemonType::CUSTOM,
        processes: 1,
        command: 'php artisan my:command',
    );

    $array = $data->toArray();

    expect($array['type'])->toBe(DaemonType::CUSTOM->value);
    expect($array['processes'])->toBe(1);
    expect($array['command'])->toBe('php artisan my:command');
});

it('excludes optional fields when not set', function () {
    $data = new CreateBackgroundProcessData(
        type: DaemonType::CUSTOM,
        processes: 1,
    );

    $array = $data->toArray();

    expect($array)->toHaveKey('type');
    expect($array)->toHaveKey('processes');
    expect($array)->not->toHaveKey('command');
    expect($array)->not->toHaveKey('config');
});

it('serializes nested config when set', function () {
    $config = new BackgroundProcessConfigData(
        connection: 'redis',
        queue: 'default',
        tries: 3,
    );

    $data = new CreateBackgroundProcessData(
        type: DaemonType::WORKER,
        processes: 2,
        config: $config,
    );

    $array = $data->toArray();

    expect($array['config']['connection'])->toBe('redis');
    expect($array['config']['queue'])->toBe('default');
    expect($array['config']['tries'])->toBe(3);
    expect($array['config'])->not->toHaveKey('timeout');
});
