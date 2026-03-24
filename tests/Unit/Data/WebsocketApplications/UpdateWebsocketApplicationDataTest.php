<?php

use Redberry\LaravelCloudSdk\Data\WebsocketApplications\UpdateWebsocketApplicationData;
use Spatie\LaravelData\Optional;

it('defaults all parameters to Optional', function () {
    $data = new UpdateWebsocketApplicationData;

    expect($data->name)->toBeInstanceOf(Optional::class);
    expect($data->pingInterval)->toBeInstanceOf(Optional::class);
    expect($data->activityTimeout)->toBeInstanceOf(Optional::class);
    expect($data->allowedOrigins)->toBeInstanceOf(Optional::class);
});

it('serializes set fields as snake_case and excludes unset optionals', function () {
    $data = new UpdateWebsocketApplicationData(
        name: 'updated-app',
        pingInterval: 45,
    );

    $array = $data->toArray();

    expect($array)->toHaveKey('name');
    expect($array)->toHaveKey('ping_interval');
    expect($array)->not->toHaveKey('pingInterval');
    expect($array)->not->toHaveKey('activityTimeout');
    expect($array)->not->toHaveKey('activity_timeout');
    expect($array['name'])->toBe('updated-app');
    expect($array['ping_interval'])->toBe(45);
});
