<?php

use Redberry\LaravelCloudSdk\Data\Buckets\BucketData;
use Redberry\LaravelCloudSdk\Connectors\LaravelCloudConnector;
use Redberry\LaravelCloudSdk\Requests\Buckets\GetBucketRequest;
use Redberry\LaravelCloudSdk\Requests\Buckets\ListBucketsRequest;
use Saloon\Enums\Method;
use Saloon\Laravel\Facades\Saloon;
use Redberry\LaravelCloudSdk\Tests\Fixtures\LaravelCloudFixture;

it('resolves the endpoint correctly', function () {
    $request = new GetBucketRequest('bucket-123');

    expect($request->resolveEndpoint())->toBe('/buckets/bucket-123');
});

it('has the correct HTTP method', function () {
    $request = new GetBucketRequest('bucket-123');

    expect($request->getMethod())->toBe(Method::GET);
});

it('gets a bucket and returns BucketData', function () {
    Saloon::fake([
        ListBucketsRequest::class => new LaravelCloudFixture('buckets/list'),
    ]);

    $connector = new LaravelCloudConnector(config('laravel-cloud-sdk.token'));
    $listResponse = $connector->send(new ListBucketsRequest);
    $firstBucket = $listResponse->dtoOrFail()->first();

    Saloon::fake([
        GetBucketRequest::class => new LaravelCloudFixture('buckets/get'),
    ]);

    $response = $connector->send(new GetBucketRequest($firstBucket->id));

    Saloon::assertSent(GetBucketRequest::class);

    $dto = $response->dtoOrFail();
    expect($dto)->toBeInstanceOf(BucketData::class);
});
