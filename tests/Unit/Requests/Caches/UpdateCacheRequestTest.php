<?php

use Redberry\LaravelCloudSdk\Connectors\LaravelCloudConnector;
use Redberry\LaravelCloudSdk\Data\Caches\CacheConnectionData;
use Redberry\LaravelCloudSdk\Data\Caches\CacheData;
use Redberry\LaravelCloudSdk\Data\Caches\UpdateCacheData;
use Redberry\LaravelCloudSdk\Enums\CacheProtocol;
use Redberry\LaravelCloudSdk\Enums\CacheSize;
use Redberry\LaravelCloudSdk\Enums\CacheStatus;
use Redberry\LaravelCloudSdk\Enums\CacheType;
use Redberry\LaravelCloudSdk\Enums\CloudRegion;
use Redberry\LaravelCloudSdk\Enums\EvictionPolicy;
use Redberry\LaravelCloudSdk\Requests\Caches\ListCachesRequest;
use Redberry\LaravelCloudSdk\Requests\Caches\UpdateCacheRequest;
use Redberry\LaravelCloudSdk\Tests\Fixtures\LaravelCloudFixture;
use Saloon\Enums\Method;
use Saloon\Laravel\Facades\Saloon;

it('resolves the endpoint correctly', function () {
    $data = new UpdateCacheData(name: 'updated-cache');
    $request = new UpdateCacheRequest('cache-123', $data);

    expect($request->resolveEndpoint())->toBe('/caches/cache-123');
});

it('has the correct HTTP method', function () {
    $data = new UpdateCacheData(name: 'updated-cache');
    $request = new UpdateCacheRequest('cache-123', $data);

    expect($request->getMethod())->toBe(Method::PATCH);
});

it('sends correct body with all optional fields', function () {
    $data = new UpdateCacheData(
        name: 'updated-cache',
        size: CacheSize::VALKEY_PRO_250MB,
        autoUpgradeEnabled: true,
        isPublic: false,
        evictionPolicy: EvictionPolicy::ALLKEYS_LRU,
    );
    $request = new UpdateCacheRequest('cache-123', $data);
    $body = $request->body()->all();

    expect($body['name'])->toBe('updated-cache');
    expect($body['size'])->toBe('valkey-pro.250mb');
    expect($body['auto_upgrade_enabled'])->toBeTrue();
    expect($body['is_public'])->toBeFalse();
    expect($body['eviction_policy'])->toBe('allkeys-lru');
});

it('excludes unset optional fields from body', function () {
    $data = new UpdateCacheData(name: 'updated-cache');
    $request = new UpdateCacheRequest('cache-123', $data);
    $body = $request->body()->all();

    expect($body)->toHaveKey('name');
    expect($body)->not->toHaveKey('size');
    expect($body)->not->toHaveKey('auto_upgrade_enabled');
    expect($body)->not->toHaveKey('is_public');
    expect($body)->not->toHaveKey('eviction_policy');
});

it('updates a cache and returns CacheData with all fields', function () {
    Saloon::fake([
        ListCachesRequest::class => new LaravelCloudFixture('caches/list'),
    ]);

    $connector = new LaravelCloudConnector(config('laravel-cloud-sdk.token'));
    $listResponse = $connector->send(new ListCachesRequest);
    $firstCache = $listResponse->dtoOrFail()[0];

    Saloon::fake([
        UpdateCacheRequest::class => new LaravelCloudFixture('caches/update'),
    ]);

    $data = new UpdateCacheData(name: 'updated-cache');
    $response = $connector->send(new UpdateCacheRequest($firstCache->id, $data));

    Saloon::assertSent(UpdateCacheRequest::class);

    $dto = $response->dtoOrFail();
    expect($dto)->toBeInstanceOf(CacheData::class);
    expect($dto->id)->toBe('cache-a14df861-12f8-413c-93b2-3c2b92e590c3');
    expect($dto->name)->toBe('updated-cache');
    expect($dto->type)->toBe(CacheType::LARAVEL_VALKEY);
    expect($dto->status)->toBe(CacheStatus::UPDATING);
    expect($dto->region)->toBe(CloudRegion::US_EAST_1);
    expect($dto->size)->toBe(CacheSize::VALKEY_PRO_250MB);
    expect($dto->autoUpgradeEnabled)->toBeTrue();
    expect($dto->isPublic)->toBeFalse();
    expect($dto->connection)->toBeInstanceOf(CacheConnectionData::class);
    expect($dto->connection->hostname)->toBeString();
    expect($dto->connection->port)->toBe(6379);
    expect($dto->connection->protocol)->toBe(CacheProtocol::REDIS);
    expect($dto->connection->username)->toBeString();
    expect($dto->connection->password)->toBeString();
    expect($dto->createdAt)->not->toBeNull();
});
