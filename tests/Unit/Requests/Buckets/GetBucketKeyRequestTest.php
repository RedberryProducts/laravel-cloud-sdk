<?php

use Redberry\LaravelCloudSdk\Connectors\LaravelCloudConnector;
use Redberry\LaravelCloudSdk\Data\Buckets\BucketKeyData;
use Redberry\LaravelCloudSdk\Requests\Buckets\GetBucketKeyRequest;
use Redberry\LaravelCloudSdk\Requests\Buckets\ListBucketKeysRequest;
use Redberry\LaravelCloudSdk\Requests\Buckets\ListBucketsRequest;
use Redberry\LaravelCloudSdk\Tests\Fixtures\LaravelCloudFixture;
use Saloon\Enums\Method;
use Saloon\Laravel\Facades\Saloon;

it('resolves the endpoint correctly', function () {
    $request = new GetBucketKeyRequest('key-123');

    expect($request->resolveEndpoint())->toBe('/bucket-keys/key-123');
});

it('has the correct HTTP method', function () {
    $request = new GetBucketKeyRequest('key-123');

    expect($request->getMethod())->toBe(Method::GET);
});

it('gets a bucket key and returns BucketKeyData', function () {
    Saloon::fake([
        ListBucketsRequest::class => new LaravelCloudFixture('buckets/list'),
    ]);

    $connector = new LaravelCloudConnector(config('laravel-cloud-sdk.token'));
    $firstBucket = $connector->send(new ListBucketsRequest)->dtoOrFail()[0];

    Saloon::fake([
        ListBucketKeysRequest::class => new LaravelCloudFixture('bucket-keys/list'),
    ]);

    $firstKey = $connector->send(new ListBucketKeysRequest($firstBucket->id))->dtoOrFail()[0];

    Saloon::fake([
        GetBucketKeyRequest::class => new LaravelCloudFixture('bucket-keys/get'),
    ]);

    $response = $connector->send(new GetBucketKeyRequest($firstKey->id));

    Saloon::assertSent(GetBucketKeyRequest::class);

    $dto = $response->dtoOrFail();
    expect($dto)->toBeInstanceOf(BucketKeyData::class);
});
