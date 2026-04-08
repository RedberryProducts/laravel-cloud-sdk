<?php

use Redberry\LaravelCloudSdk\Connectors\LaravelCloudConnector;
use Redberry\LaravelCloudSdk\Data\Caches\CreateCacheData;
use Redberry\LaravelCloudSdk\Enums\CacheSize;
use Redberry\LaravelCloudSdk\Enums\CacheType;
use Redberry\LaravelCloudSdk\Enums\CloudRegion;
use Redberry\LaravelCloudSdk\Requests\Caches\CreateCacheRequest;
use Redberry\LaravelCloudSdk\Requests\Caches\DeleteCacheRequest;
use Redberry\LaravelCloudSdk\Tests\Fixtures\LaravelCloudFixture;
use Saloon\Enums\Method;
use Saloon\Laravel\Facades\Saloon;

it('resolves the endpoint correctly', function () {
    $request = new DeleteCacheRequest('cache-123');

    expect($request->resolveEndpoint())->toBe('/caches/cache-123');
});

it('has the correct HTTP method', function () {
    $request = new DeleteCacheRequest('cache-123');

    expect($request->getMethod())->toBe(Method::DELETE);
});

it('sends the delete request successfully', function () {
    Saloon::fake([
        CreateCacheRequest::class => new LaravelCloudFixture('caches/delete-create'),
        DeleteCacheRequest::class => new LaravelCloudFixture('caches/delete'),
    ]);

    $connector = new LaravelCloudConnector(config('laravel-cloud-sdk.token'));
    $cache = $connector->send(new CreateCacheRequest(new CreateCacheData(
        type: CacheType::LARAVEL_VALKEY,
        name: 'sdk-delete-test',
        region: CloudRegion::US_EAST_1,
        size: CacheSize::VALKEY_PRO_250MB,
        autoUpgradeEnabled: false,
        isPublic: false,
    )))->dtoOrFail();

    $response = $connector->send(new DeleteCacheRequest($cache->id));

    Saloon::assertSent(DeleteCacheRequest::class);
    expect($response->successful())->toBeTrue();
});
