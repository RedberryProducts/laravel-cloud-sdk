<?php

use Redberry\LaravelCloudSdk\Connectors\LaravelCloudConnector;
use Redberry\LaravelCloudSdk\Requests\Caches\DeleteCacheRequest;
use Redberry\LaravelCloudSdk\Requests\Caches\ListCachesRequest;
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
        ListCachesRequest::class => new LaravelCloudFixture('caches/list'),
        DeleteCacheRequest::class => new LaravelCloudFixture('caches/delete'),
    ]);

    $connector = new LaravelCloudConnector(config('laravel-cloud-sdk.token'));
    $firstCache = $connector->send(new ListCachesRequest)->dtoOrFail()->first();

    $response = $connector->send(new DeleteCacheRequest($firstCache->id));

    Saloon::assertSent(DeleteCacheRequest::class);
    expect($response->successful())->toBeTrue();
})->skip('Fixture pending: record in Phase 10.');
