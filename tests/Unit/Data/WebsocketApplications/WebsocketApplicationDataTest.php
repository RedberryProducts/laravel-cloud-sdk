<?php

use Carbon\CarbonImmutable;
use Redberry\LaravelCloudSdk\Data\WebsocketApplications\WebsocketApplicationData;

it('can be created from API response data', function () {
    $data = WebsocketApplicationData::fromResponse([
        'name' => 'my-app',
        'app_id' => 'app-abc123',
        'allowed_origins' => ['https://example.com'],
        'ping_interval' => 60,
        'activity_timeout' => 30,
        'max_message_size' => 10000,
        'max_connections' => 100,
        'key' => 'REDACTED',
        'secret' => 'REDACTED',
        'created_at' => '2024-06-15T10:30:00Z',
    ], 'ws-app-123');

    expect($data)->toBeInstanceOf(WebsocketApplicationData::class);
    expect($data->id)->toBe('ws-app-123');
    expect($data->name)->toBe('my-app');
    expect($data->appId)->toBe('app-abc123');
    expect($data->allowedOrigins)->toBe(['https://example.com']);
    expect($data->pingInterval)->toBe(60);
    expect($data->activityTimeout)->toBe(30);
    expect($data->maxMessageSize)->toBe(10000);
    expect($data->maxConnections)->toBe(100);
    expect($data->key)->toBe('REDACTED');
    expect($data->secret)->toBe('REDACTED');
    expect($data->createdAt)->toBeInstanceOf(CarbonImmutable::class);
});

it('handles null created_at', function () {
    $data = WebsocketApplicationData::fromResponse([
        'name' => 'my-app',
        'app_id' => 'app-abc123',
        'allowed_origins' => [],
        'ping_interval' => 60,
        'activity_timeout' => 30,
        'max_message_size' => 10000,
        'max_connections' => 100,
        'key' => 'REDACTED',
        'secret' => 'REDACTED',
        'created_at' => null,
    ], 'ws-app-456');

    expect($data->allowedOrigins)->toBe([]);
    expect($data->createdAt)->toBeNull();
});
