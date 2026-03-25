<?php

use Illuminate\Support\Collection;
use Redberry\LaravelCloudSdk\Connectors\LaravelCloudConnector;
use Redberry\LaravelCloudSdk\Data\Buckets\BucketKeyData;
use Redberry\LaravelCloudSdk\Requests\Buckets\ListBucketKeysRequest;
use Redberry\LaravelCloudSdk\Requests\Buckets\ListBucketsRequest;
use Redberry\LaravelCloudSdk\Tests\Fixtures\LaravelCloudFixture;
use Saloon\Enums\Method;
use Saloon\Laravel\Facades\Saloon;

it('resolves the endpoint correctly', function () {
    $request = new ListBucketKeysRequest('bucket-123');

    expect($request->resolveEndpoint())->toBe('/buckets/bucket-123/keys');
});

it('has the correct HTTP method', function () {
    $request = new ListBucketKeysRequest('bucket-123');

    expect($request->getMethod())->toBe(Method::GET);
});

it('lists bucket keys and returns BucketKeyData collection', function () {
    Saloon::fake([
        ListBucketsRequest::class => new LaravelCloudFixture('buckets/list'),
    ]);

    $connector = new LaravelCloudConnector(config('laravel-cloud-sdk.token'));
    $listResponse = $connector->send(new ListBucketsRequest);
    $firstBucket = $listResponse->dtoOrFail()->first();

    Saloon::fake([
        ListBucketKeysRequest::class => new LaravelCloudFixture('bucket-keys/list'),
    ]);

    $response = $connector->send(new ListBucketKeysRequest($firstBucket->id));

    Saloon::assertSent(ListBucketKeysRequest::class);

    $dto = $response->dtoOrFail();
    expect($dto)->toBeInstanceOf(Collection::class);
    expect($dto->first())->toBeInstanceOf(BucketKeyData::class);
});
