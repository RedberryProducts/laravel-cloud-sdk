<?php

use Redberry\LaravelCloudSdk\Data\Caches\UpdateCacheData;
use Redberry\LaravelCloudSdk\Enums\CacheSize;
use Redberry\LaravelCloudSdk\Enums\EvictionPolicy;
use Spatie\LaravelData\Optional;

it('can be constructed with partial parameters', function () {
    $data = new UpdateCacheData(
        name: 'new-name',
        size: CacheSize::Upstash1Gb,
    );

    expect($data->name)->toBe('new-name');
    expect($data->size)->toBe(CacheSize::Upstash1Gb);
    expect($data->autoUpgradeEnabled)->toBeInstanceOf(Optional::class);
    expect($data->isPublic)->toBeInstanceOf(Optional::class);
    expect($data->evictionPolicy)->toBeInstanceOf(Optional::class);
});

it('can be constructed with all parameters', function () {
    $data = new UpdateCacheData(
        name: 'updated-cache',
        size: CacheSize::Upstash2_5Gb,
        autoUpgradeEnabled: false,
        isPublic: true,
        evictionPolicy: EvictionPolicy::NoEviction,
    );

    expect($data->name)->toBe('updated-cache');
    expect($data->size)->toBe(CacheSize::Upstash2_5Gb);
    expect($data->autoUpgradeEnabled)->toBeFalse();
    expect($data->isPublic)->toBeTrue();
    expect($data->evictionPolicy)->toBe(EvictionPolicy::NoEviction);
});

it('defaults all parameters to Optional', function () {
    $data = new UpdateCacheData;

    expect($data->name)->toBeInstanceOf(Optional::class);
    expect($data->size)->toBeInstanceOf(Optional::class);
    expect($data->autoUpgradeEnabled)->toBeInstanceOf(Optional::class);
    expect($data->isPublic)->toBeInstanceOf(Optional::class);
    expect($data->evictionPolicy)->toBeInstanceOf(Optional::class);
});
