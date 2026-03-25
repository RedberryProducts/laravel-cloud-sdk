<?php

use Illuminate\Support\Collection;
use Redberry\LaravelCloudSdk\Connectors\LaravelCloudConnector;
use Redberry\LaravelCloudSdk\Data\Caches\CacheData;
use Redberry\LaravelCloudSdk\Requests\Caches\ListCachesRequest;
use Redberry\LaravelCloudSdk\Tests\Fixtures\LaravelCloudFixture;
use Saloon\Enums\Method;
use Saloon\Laravel\Facades\Saloon;

it('resolves the endpoint correctly', function () {
    $request = new ListCachesRequest;

    expect($request->resolveEndpoint())->toBe('/caches');
});

it('has the correct HTTP method', function () {
    $request = new ListCachesRequest;

    expect($request->getMethod())->toBe(Method::GET);
});

it('lists caches and returns CacheData collection', function () {
    Saloon::fake([
        ListCachesRequest::class => new LaravelCloudFixture('caches/list'),
    ]);

    $connector = new LaravelCloudConnector(config('laravel-cloud-sdk.token'));
    $response = $connector->send(new ListCachesRequest);

    Saloon::assertSent(ListCachesRequest::class);
    expect($response->getPsrRequest()->getMethod())->toBe('GET');
    expect($response->getPsrRequest()->getUri()->getPath())->toBe('/api/caches');

    $dto = $response->dtoOrFail();
    expect($dto)->toBeInstanceOf(Collection::class);
    expect($dto->first())->toBeInstanceOf(CacheData::class);
});
