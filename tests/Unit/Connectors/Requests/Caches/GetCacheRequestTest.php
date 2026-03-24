<?php

use App\Data\LaravelCloud\Caches\CacheData;
use App\Http\Integrations\LaravelCloud\LaravelCloudConnector;
use App\Http\Integrations\LaravelCloud\Requests\Caches\GetCacheRequest;
use App\Http\Integrations\LaravelCloud\Requests\Caches\ListCachesRequest;
use Saloon\Enums\Method;
use Saloon\Laravel\Facades\Saloon;
use Tests\Fixtures\LaravelCloud\LaravelCloudFixture;

it('resolves the endpoint correctly', function () {
    $request = new GetCacheRequest('cache-123');

    expect($request->resolveEndpoint())->toBe('/caches/cache-123');
});

it('has the correct HTTP method', function () {
    $request = new GetCacheRequest('cache-123');

    expect($request->getMethod())->toBe(Method::GET);
});

it('gets a cache and returns CacheData', function () {
    Saloon::fake([
        ListCachesRequest::class => new LaravelCloudFixture('caches/list'),
    ]);

    $connector = new LaravelCloudConnector(config('laravel-cloud.token'));
    $firstCache = $connector->send(new ListCachesRequest)->dtoOrFail()->first();

    Saloon::fake([
        GetCacheRequest::class => new LaravelCloudFixture('caches/get'),
    ]);

    $response = $connector->send(new GetCacheRequest($firstCache->id));

    Saloon::assertSent(GetCacheRequest::class);

    $dto = $response->dtoOrFail();
    expect($dto)->toBeInstanceOf(CacheData::class);
});
