<?php

use App\Data\LaravelCloud\Caches\CacheData;
use App\Data\LaravelCloud\Caches\UpdateCacheData;
use App\Enums\LaravelCloud\CacheSize;
use App\Http\Integrations\LaravelCloud\LaravelCloudConnector;
use App\Http\Integrations\LaravelCloud\Requests\Caches\ListCachesRequest;
use App\Http\Integrations\LaravelCloud\Requests\Caches\UpdateCacheRequest;
use Saloon\Enums\Method;
use Saloon\Laravel\Facades\Saloon;
use Tests\Fixtures\LaravelCloud\LaravelCloudFixture;

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

it('sends correct body', function () {
    $data = new UpdateCacheData(name: 'updated-cache', size: CacheSize::UPSTASH_1GB);
    $request = new UpdateCacheRequest('cache-123', $data);
    $body = $request->body()->all();

    expect($body['name'])->toBe('updated-cache');
    expect($body['size'])->toBe('1gb');
});

it('updates a cache and returns CacheData', function () {
    Saloon::fake([
        ListCachesRequest::class => new LaravelCloudFixture('caches/list'),
    ]);

    $connector = new LaravelCloudConnector(config('laravel-cloud.token'));
    $listResponse = $connector->send(new ListCachesRequest);
    $firstCache = $listResponse->dtoOrFail()->first();

    Saloon::fake([
        UpdateCacheRequest::class => new LaravelCloudFixture('caches/update'),
    ]);

    $data = new UpdateCacheData(name: 'updated-cache');
    $response = $connector->send(new UpdateCacheRequest($firstCache->id, $data));

    Saloon::assertSent(UpdateCacheRequest::class);

    $dto = $response->dtoOrFail();
    expect($dto)->toBeInstanceOf(CacheData::class);
});
