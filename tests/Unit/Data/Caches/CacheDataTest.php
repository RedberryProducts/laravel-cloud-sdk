<?php

use Redberry\LaravelCloudSdk\Data\Caches\CacheData;
use Redberry\LaravelCloudSdk\Data\Caches\CacheConnectionData;
use Redberry\LaravelCloudSdk\Enums\CacheStatus;
use Redberry\LaravelCloudSdk\Enums\CacheType;
use Redberry\LaravelCloudSdk\Enums\CacheSize;
use Redberry\LaravelCloudSdk\Enums\CloudRegion;
use Carbon\CarbonImmutable;

it('can be created from API response data', function () {
    $responseData = [
        'name' => 'my-cache',
        'type' => 'laravel_valkey',
        'status' => 'available',
        'region' => 'us-east-1',
        'size' => '250mb',
        'auto_upgrade_enabled' => true,
        'is_public' => false,
        'connection' => [
            'hostname' => 'cache.example.com',
            'port' => 6379,
            'protocol' => 'redis',
            'username' => 'default',
            'password' => 'secret',
        ],
        'created_at' => '2024-06-15T10:30:00Z',
    ];

    $data = CacheData::fromResponse($responseData, 'cache-123');

    expect($data)->toBeInstanceOf(CacheData::class);
    expect($data->id)->toBe('cache-123');
    expect($data->name)->toBe('my-cache');
    expect($data->type)->toBe(CacheType::LARAVEL_VALKEY);
    expect($data->status)->toBe(CacheStatus::AVAILABLE);
    expect($data->region)->toBe(CloudRegion::US_EAST_1);
    expect($data->size)->toBe(CacheSize::UPSTASH_250MB);
    expect($data->autoUpgradeEnabled)->toBeTrue();
    expect($data->isPublic)->toBeFalse();
    expect($data->connection)->toBeInstanceOf(CacheConnectionData::class);
    expect($data->connection->hostname)->toBe('cache.example.com');
    expect($data->createdAt)->toBeInstanceOf(CarbonImmutable::class);
});

it('handles null created_at', function () {
    $responseData = [
        'name' => 'my-cache',
        'type' => 'upstash_redis',
        'status' => 'creating',
        'region' => 'eu-central-1',
        'size' => '1gb',
        'auto_upgrade_enabled' => false,
        'is_public' => true,
        'connection' => [
            'hostname' => null,
            'port' => 6379,
            'protocol' => 'fake',
            'username' => null,
            'password' => null,
        ],
    ];

    $data = CacheData::fromResponse($responseData, 'cache-456');

    expect($data->createdAt)->toBeNull();
    expect($data->type)->toBe(CacheType::UPSTASH_REDIS);
    expect($data->status)->toBe(CacheStatus::CREATING);
});
