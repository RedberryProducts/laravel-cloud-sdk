<?php

use Illuminate\Support\Collection;
use Illuminate\Support\LazyCollection;
use Redberry\LaravelCloudSdk\Data\Caches\CacheData;
use Redberry\LaravelCloudSdk\Data\Caches\CacheTypeData;
use Redberry\LaravelCloudSdk\Data\Caches\CreateCacheData;
use Redberry\LaravelCloudSdk\Data\Caches\UpdateCacheData;
use Redberry\LaravelCloudSdk\Enums\CacheSize;
use Redberry\LaravelCloudSdk\Enums\CacheType;
use Redberry\LaravelCloudSdk\Enums\CloudRegion;
use Redberry\LaravelCloudSdk\LaravelCloud;
use Redberry\LaravelCloudSdk\Requests\Caches\CreateCacheRequest;
use Redberry\LaravelCloudSdk\Requests\Caches\DeleteCacheRequest;
use Redberry\LaravelCloudSdk\Requests\Caches\GetCacheRequest;
use Redberry\LaravelCloudSdk\Requests\Caches\ListCachesRequest;
use Redberry\LaravelCloudSdk\Requests\Caches\ListCacheTypesRequest;
use Redberry\LaravelCloudSdk\Requests\Caches\UpdateCacheRequest;
use Redberry\LaravelCloudSdk\Tests\Fixtures\LaravelCloudFixture;
use Saloon\Laravel\Facades\Saloon;

it('lists caches', function () {
    Saloon::fake([
        ListCachesRequest::class => new LaravelCloudFixture('caches/list'),
    ]);

    $result = (new LaravelCloud('token'))->caches();

    expect($result)->toBeInstanceOf(LazyCollection::class);
    expect($result->first())->toBeInstanceOf(CacheData::class);
    Saloon::assertSent(ListCachesRequest::class);
});

it('retrieves a single cache by id', function () {
    Saloon::fake([
        GetCacheRequest::class => new LaravelCloudFixture('caches/get'),
    ]);

    $result = (new LaravelCloud('token'))->cache('cache-a14df861-12f8-413c-93b2-3c2b92e590c3');

    Saloon::assertSent(GetCacheRequest::class);
    expect($result)->toBeInstanceOf(CacheData::class);
    expect($result->id)->toBe('cache-a14df861-12f8-413c-93b2-3c2b92e590c3');
    expect($result->name)->toBe('updated-cache');
});

it('creates a cache with named params', function () {
    Saloon::fake([
        CreateCacheRequest::class => new LaravelCloudFixture('caches/create'),
    ]);

    $result = (new LaravelCloud('token'))->createCache(
        type: CacheType::LARAVEL_VALKEY,
        name: 'production-cache',
        region: CloudRegion::US_EAST_1,
        size: CacheSize::VALKEY_PRO_250MB,
        autoUpgradeEnabled: true,
        isPublic: false,
    );

    Saloon::assertSent(CreateCacheRequest::class);
    expect($result)->toBeInstanceOf(CacheData::class);
});

it('creates a cache with string enums', function () {
    Saloon::fake([
        CreateCacheRequest::class => new LaravelCloudFixture('caches/create'),
    ]);

    $result = (new LaravelCloud('token'))->createCache(
        type: 'laravel_valkey',
        name: 'production-cache',
        region: 'us-east-1',
        size: 'valkey-pro.250mb',
        autoUpgradeEnabled: true,
        isPublic: false,
    );

    Saloon::assertSent(CreateCacheRequest::class);
    expect($result)->toBeInstanceOf(CacheData::class);
});

it('creates a cache via createCacheWith()', function () {
    Saloon::fake([
        CreateCacheRequest::class => new LaravelCloudFixture('caches/create'),
    ]);

    $result = (new LaravelCloud('token'))->createCacheWith(
        new CreateCacheData(
            type: CacheType::LARAVEL_VALKEY,
            name: 'production-cache',
            region: CloudRegion::US_EAST_1,
            size: CacheSize::VALKEY_PRO_250MB,
            autoUpgradeEnabled: true,
            isPublic: false,
        )
    );

    Saloon::assertSent(CreateCacheRequest::class);
    expect($result)->toBeInstanceOf(CacheData::class);
});

it('updates a cache with named params', function () {
    Saloon::fake([
        UpdateCacheRequest::class => new LaravelCloudFixture('caches/update'),
    ]);

    $result = (new LaravelCloud('token'))->updateCache(
        'cache-a14df861-12f8-413c-93b2-3c2b92e590c3',
        name: 'updated-cache',
    );

    Saloon::assertSent(UpdateCacheRequest::class);
    expect($result)->toBeInstanceOf(CacheData::class);
    expect($result->name)->toBe('updated-cache');
});

it('updates a cache via updateCacheWith()', function () {
    Saloon::fake([
        UpdateCacheRequest::class => new LaravelCloudFixture('caches/update'),
    ]);

    $result = (new LaravelCloud('token'))->updateCacheWith(
        'cache-a14df861-12f8-413c-93b2-3c2b92e590c3',
        new UpdateCacheData(name: 'updated-cache'),
    );

    Saloon::assertSent(UpdateCacheRequest::class);
    expect($result)->toBeInstanceOf(CacheData::class);
});

it('lists available cache types', function () {
    Saloon::fake([
        ListCacheTypesRequest::class => new LaravelCloudFixture('caches/types'),
    ]);

    $result = (new LaravelCloud('token'))->cacheTypes();

    Saloon::assertSent(ListCacheTypesRequest::class);
    expect($result)->toBeInstanceOf(Collection::class);
    expect($result->first())->toBeInstanceOf(CacheTypeData::class);
});

it('deletes a cache', function () {
    Saloon::fake([
        DeleteCacheRequest::class => new LaravelCloudFixture('caches/delete'),
    ]);

    (new LaravelCloud('token'))->deleteCache('cache-a14df861-12f8-413c-93b2-3c2b92e590c3');

    Saloon::assertSent(DeleteCacheRequest::class);
});
