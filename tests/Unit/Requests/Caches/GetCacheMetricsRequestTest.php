<?php

use Redberry\LaravelCloudSdk\Connectors\LaravelCloudConnector;
use Redberry\LaravelCloudSdk\Data\Caches\CacheMetricsData;
use Redberry\LaravelCloudSdk\Enums\MetricPeriod;
use Redberry\LaravelCloudSdk\Requests\Caches\GetCacheMetricsRequest;
use Redberry\LaravelCloudSdk\Requests\Caches\ListCachesRequest;
use Redberry\LaravelCloudSdk\Tests\Fixtures\LaravelCloudFixture;
use Saloon\Enums\Method;
use Saloon\Laravel\Facades\Saloon;

it('resolves the endpoint correctly', function () {
    $request = new GetCacheMetricsRequest('cache-123');

    expect($request->resolveEndpoint())->toBe('/caches/cache-123/metrics');
});

it('has the correct HTTP method', function () {
    $request = new GetCacheMetricsRequest('cache-123');

    expect($request->getMethod())->toBe(Method::GET);
});

it('includes period in query when provided as enum', function () {
    $request = new GetCacheMetricsRequest('cache-123', MetricPeriod::TwentyFourHours);

    expect($request->query()->all())->toBe(['period' => '24h']);
});

it('omits period from query when null', function () {
    $request = new GetCacheMetricsRequest('cache-123');

    expect($request->query()->all())->toBe([]);
});

it('gets cache metrics and returns CacheMetricsData', function () {
    Saloon::fake([
        ListCachesRequest::class => new LaravelCloudFixture('caches/metrics-list'),
    ]);

    $connector = new LaravelCloudConnector(config('laravel-cloud-sdk.token'));
    $firstCache = $connector->send(new ListCachesRequest)->dtoOrFail()[0];

    Saloon::fake([
        GetCacheMetricsRequest::class => new LaravelCloudFixture('caches/metrics'),
    ]);

    $response = $connector->send(new GetCacheMetricsRequest($firstCache->id));

    Saloon::assertSent(GetCacheMetricsRequest::class);

    $dto = $response->dtoOrFail();
    expect($dto)->toBeInstanceOf(CacheMetricsData::class);
});
