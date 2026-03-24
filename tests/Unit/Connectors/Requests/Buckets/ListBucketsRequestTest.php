<?php

use App\Data\LaravelCloud\Buckets\BucketData;
use App\Http\Integrations\LaravelCloud\LaravelCloudConnector;
use App\Http\Integrations\LaravelCloud\Requests\Buckets\ListBucketsRequest;
use Illuminate\Support\Collection;
use Saloon\Enums\Method;
use Saloon\Laravel\Facades\Saloon;
use Tests\Fixtures\LaravelCloud\LaravelCloudFixture;

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

    $connector = new LaravelCloudConnector(config('laravel-cloud.token'));
    $response = $connector->send(new ListBucketsRequest);

    Saloon::assertSent(ListBucketsRequest::class);
    expect($response->getPsrRequest()->getMethod())->toBe('GET');
    expect($response->getPsrRequest()->getUri()->getPath())->toBe('/api/buckets');

    $dto = $response->dtoOrFail();
    expect($dto)->toBeInstanceOf(Collection::class);
    expect($dto->first())->toBeInstanceOf(BucketData::class);
});
