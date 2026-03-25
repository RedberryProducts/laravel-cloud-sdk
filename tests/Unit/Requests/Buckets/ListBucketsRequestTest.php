<?php

use Illuminate\Support\Collection;
use Redberry\LaravelCloudSdk\Connectors\LaravelCloudConnector;
use Redberry\LaravelCloudSdk\Data\Buckets\BucketData;
use Redberry\LaravelCloudSdk\Requests\Buckets\ListBucketsRequest;
use Redberry\LaravelCloudSdk\Tests\Fixtures\LaravelCloudFixture;
use Saloon\Enums\Method;
use Saloon\Laravel\Facades\Saloon;

it('resolves the endpoint correctly', function () {
    $request = new ListBucketsRequest;

    expect($request->resolveEndpoint())->toBe('/buckets');
});

it('has the correct HTTP method', function () {
    $request = new ListBucketsRequest;

    expect($request->getMethod())->toBe(Method::GET);
});

it('lists buckets and returns BucketData collection', function () {
    Saloon::fake([
        ListBucketsRequest::class => new LaravelCloudFixture('buckets/list'),
    ]);

    $connector = new LaravelCloudConnector(config('laravel-cloud-sdk.token'));
    $response = $connector->send(new ListBucketsRequest);

    Saloon::assertSent(ListBucketsRequest::class);
    expect($response->getPsrRequest()->getMethod())->toBe('GET');
    expect($response->getPsrRequest()->getUri()->getPath())->toBe('/api/buckets');

    $dto = $response->dtoOrFail();
    expect($dto)->toBeInstanceOf(Collection::class);
    expect($dto->first())->toBeInstanceOf(BucketData::class);
});
