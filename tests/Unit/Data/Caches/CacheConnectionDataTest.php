<?php

use Redberry\LaravelCloudSdk\Data\Caches\CacheConnectionData;
use Redberry\LaravelCloudSdk\Enums\CacheProtocol;

it('can be constructed with all parameters', function () {
    $data = new CacheConnectionData(
        hostname: 'cache.example.com',
        port: 6379,
        protocol: CacheProtocol::REDIS,
        username: 'default',
        password: 'secret',
    );

    expect($data->hostname)->toBe('cache.example.com');
    expect($data->port)->toBe(6379);
    expect($data->protocol)->toBe(CacheProtocol::REDIS);
    expect($data->username)->toBe('default');
    expect($data->password)->toBe('secret');
});

it('can be created from API response data', function () {
    $responseData = [
        'hostname' => 'redis.cloud.example.com',
        'port' => 6380,
        'protocol' => 'redis',
        'username' => 'admin',
        'password' => 'password123',
    ];

    $data = CacheConnectionData::fromResponse($responseData);

    expect($data)->toBeInstanceOf(CacheConnectionData::class);
    expect($data->hostname)->toBe('redis.cloud.example.com');
    expect($data->port)->toBe(6380);
    expect($data->protocol)->toBe(CacheProtocol::REDIS);
    expect($data->username)->toBe('admin');
    expect($data->password)->toBe('password123');
});

it('handles nullable hostname, username and password', function () {
    $data = new CacheConnectionData(
        hostname: null,
        port: 6379,
        protocol: CacheProtocol::FAKE,
        username: null,
        password: null,
    );

    expect($data->hostname)->toBeNull();
    expect($data->username)->toBeNull();
    expect($data->password)->toBeNull();
    expect($data->protocol)->toBe(CacheProtocol::FAKE);
});
