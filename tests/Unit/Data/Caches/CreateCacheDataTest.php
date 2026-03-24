<?php

use App\Data\LaravelCloud\Caches\CreateCacheData;
use App\Enums\LaravelCloud\CacheSize;
use App\Enums\LaravelCloud\CacheType;
use App\Enums\LaravelCloud\CloudRegion;
use App\Enums\LaravelCloud\EvictionPolicy;

it('can be constructed with required parameters', function () {
    $data = new CreateCacheData(
        type: CacheType::LARAVEL_VALKEY,
        name: 'test-cache',
        region: CloudRegion::US_EAST_1,
        size: CacheSize::VALKEY_PRO_250MB,
        autoUpgradeEnabled: true,
        isPublic: false,
    );

    expect($data->type)->toBe(CacheType::LARAVEL_VALKEY);
    expect($data->name)->toBe('test-cache');
    expect($data->region)->toBe(CloudRegion::US_EAST_1);
    expect($data->size)->toBe(CacheSize::VALKEY_PRO_250MB);
    expect($data->autoUpgradeEnabled)->toBeTrue();
    expect($data->isPublic)->toBeFalse();
    expect($data->evictionPolicy)->toBeNull();
});

it('can be constructed with optional eviction policy', function () {
    $data = new CreateCacheData(
        type: CacheType::LARAVEL_VALKEY,
        name: 'test-cache',
        region: CloudRegion::US_EAST_1,
        size: CacheSize::VALKEY_PRO_1GB,
        autoUpgradeEnabled: false,
        isPublic: true,
        evictionPolicy: EvictionPolicy::ALLKEYS_LRU,
    );

    expect($data->evictionPolicy)->toBe(EvictionPolicy::ALLKEYS_LRU);
});
