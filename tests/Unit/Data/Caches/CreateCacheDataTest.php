<?php

use Redberry\LaravelCloudSdk\Data\Caches\CreateCacheData;
use Redberry\LaravelCloudSdk\Enums\CacheSize;
use Redberry\LaravelCloudSdk\Enums\CacheType;
use Redberry\LaravelCloudSdk\Enums\CloudRegion;
use Redberry\LaravelCloudSdk\Enums\EvictionPolicy;

it('can be constructed with required parameters', function () {
    $data = new CreateCacheData(
        type: CacheType::LaravelValkey,
        name: 'test-cache',
        region: CloudRegion::UsEast1,
        size: CacheSize::ValkeyPro250Mb,
        autoUpgradeEnabled: true,
        isPublic: false,
    );

    expect($data->type)->toBe(CacheType::LaravelValkey);
    expect($data->name)->toBe('test-cache');
    expect($data->region)->toBe(CloudRegion::UsEast1);
    expect($data->size)->toBe(CacheSize::ValkeyPro250Mb);
    expect($data->autoUpgradeEnabled)->toBeTrue();
    expect($data->isPublic)->toBeFalse();
    expect($data->evictionPolicy)->toBeNull();
});

it('can be constructed with optional eviction policy', function () {
    $data = new CreateCacheData(
        type: CacheType::LaravelValkey,
        name: 'test-cache',
        region: CloudRegion::UsEast1,
        size: CacheSize::ValkeyPro1Gb,
        autoUpgradeEnabled: false,
        isPublic: true,
        evictionPolicy: EvictionPolicy::AllKeysLru,
    );

    expect($data->evictionPolicy)->toBe(EvictionPolicy::AllKeysLru);
});
