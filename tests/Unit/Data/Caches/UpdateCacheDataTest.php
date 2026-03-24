<?php

use Redberry\LaravelCloudSdk\Data\Caches\UpdateCacheData;
use Redberry\LaravelCloudSdk\Enums\CacheSize;
use Redberry\LaravelCloudSdk\Enums\EvictionPolicy;
use Spatie\LaravelData\Optional;

it('can be constructed with partial parameters', function () {
    $data = new UpdateCacheData(
        name: 'new-name',
        size: CacheSize::UPSTASH_1GB,
    );

    expect($data->name)->toBe('new-name');
    expect($data->size)->toBe(CacheSize::UPSTASH_1GB);
    expect($data->autoUpgradeEnabled)->toBeInstanceOf(Optional::class);
    expect($data->isPublic)->toBeInstanceOf(Optional::class);
    expect($data->evictionPolicy)->toBeInstanceOf(Optional::class);
});

it('can be constructed with all parameters', function () {
    $data = new UpdateCacheData(
        name: 'updated-cache',
        size: CacheSize::UPSTASH_2_5GB,
        autoUpgradeEnabled: false,
        isPublic: true,
        evictionPolicy: EvictionPolicy::NOEVICTION,
    );

    expect($data->name)->toBe('updated-cache');
    expect($data->size)->toBe(CacheSize::UPSTASH_2_5GB);
    expect($data->autoUpgradeEnabled)->toBeFalse();
    expect($data->isPublic)->toBeTrue();
    expect($data->evictionPolicy)->toBe(EvictionPolicy::NOEVICTION);
});

it('defaults all parameters to Optional', function () {
    $data = new UpdateCacheData;

    expect($data->name)->toBeInstanceOf(Optional::class);
    expect($data->size)->toBeInstanceOf(Optional::class);
    expect($data->autoUpgradeEnabled)->toBeInstanceOf(Optional::class);
    expect($data->isPublic)->toBeInstanceOf(Optional::class);
    expect($data->evictionPolicy)->toBeInstanceOf(Optional::class);
});
