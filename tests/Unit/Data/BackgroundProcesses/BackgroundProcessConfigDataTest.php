<?php

use Redberry\LaravelCloudSdk\Data\BackgroundProcesses\BackgroundProcessConfigData;
use Spatie\LaravelData\Optional;

it('defaults all fields to Optional', function () {
    $data = new BackgroundProcessConfigData;

    expect($data->connection)->toBeInstanceOf(Optional::class);
    expect($data->queue)->toBeInstanceOf(Optional::class);
    expect($data->tries)->toBeInstanceOf(Optional::class);
    expect($data->backoff)->toBeInstanceOf(Optional::class);
    expect($data->sleep)->toBeInstanceOf(Optional::class);
    expect($data->rest)->toBeInstanceOf(Optional::class);
    expect($data->timeout)->toBeInstanceOf(Optional::class);
    expect($data->force)->toBeInstanceOf(Optional::class);
});

it('serializes set fields as snake_case', function () {
    $data = new BackgroundProcessConfigData(
        connection: 'redis',
        queue: 'default',
        tries: 3,
        backoff: 5,
        sleep: 1,
        rest: 2,
        timeout: 60,
        force: false,
    );

    $array = $data->toArray();

    expect($array['connection'])->toBe('redis');
    expect($array['queue'])->toBe('default');
    expect($array['tries'])->toBe(3);
    expect($array['backoff'])->toBe(5);
    expect($array['sleep'])->toBe(1);
    expect($array['rest'])->toBe(2);
    expect($array['timeout'])->toBe(60);
    expect($array['force'])->toBeFalse();
});

it('excludes unset optional fields', function () {
    $data = new BackgroundProcessConfigData(connection: 'redis');

    $array = $data->toArray();

    expect($array)->toHaveKey('connection');
    expect($array)->not->toHaveKey('queue');
    expect($array)->not->toHaveKey('tries');
    expect($array)->not->toHaveKey('force');
});

it('builds from response attributes', function () {
    $data = BackgroundProcessConfigData::fromResponse([
        'connection' => 'redis',
        'queue' => 'emails',
        'tries' => 5,
        'timeout' => 120,
    ]);

    expect($data->connection)->toBe('redis');
    expect($data->queue)->toBe('emails');
    expect($data->tries)->toBe(5);
    expect($data->timeout)->toBe(120);
    expect($data->backoff)->toBeInstanceOf(Optional::class);
    expect($data->force)->toBeInstanceOf(Optional::class);
});
