<?php

use Redberry\LaravelCloudSdk\Data\WebsocketApplications\CreateWebsocketApplicationData;
use Spatie\LaravelData\Optional;

it('can be constructed with only the required name', function () {
    $data = new CreateWebsocketApplicationData(name: 'my-app');

    expect($data->name)->toBe('my-app');
    expect($data->pingInterval)->toBeInstanceOf(Optional::class);
    expect($data->activityTimeout)->toBeInstanceOf(Optional::class);
    expect($data->allowedOrigins)->toBeInstanceOf(Optional::class);
});

it('serializes optional fields as snake_case and excludes unset optionals', function () {
    $data = new CreateWebsocketApplicationData(
        name: 'my-app',
        pingInterval: 30,
        activityTimeout: 15,
    );

    $array = $data->toArray();

    expect($array)->toHaveKey('name');
    expect($array)->toHaveKey('ping_interval');
    expect($array)->toHaveKey('activity_timeout');
    expect($array)->not->toHaveKey('pingInterval');
    expect($array)->not->toHaveKey('activityTimeout');
    expect($array)->not->toHaveKey('allowedOrigins');
    expect($array)->not->toHaveKey('allowed_origins');
    expect($array['ping_interval'])->toBe(30);
    expect($array['activity_timeout'])->toBe(15);
});
