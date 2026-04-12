<?php

use Redberry\LaravelCloudSdk\Connectors\LaravelCloudConnector;
use Redberry\LaravelCloudSdk\Data\Buckets\BucketKeyData;
use Redberry\LaravelCloudSdk\Requests\Buckets\ListBucketKeysRequest;
use Redberry\LaravelCloudSdk\Requests\Buckets\ListBucketsRequest;
use Redberry\LaravelCloudSdk\Tests\Fixtures\LaravelCloudFixture;
use Saloon\Enums\Method;
use Saloon\Laravel\Facades\Saloon;
use Saloon\PaginationPlugin\Contracts\Paginatable;

it('resolves the endpoint correctly', function () {
    $request = new ListBucketKeysRequest('bucket-123');

    expect($request->resolveEndpoint())->toBe('/buckets/bucket-123/keys');
});

it('has the correct HTTP method', function () {
    $request = new ListBucketKeysRequest('bucket-123');

    expect($request->getMethod())->toBe(Method::GET);
});

it('implements Paginatable', function () {
    $request = new ListBucketKeysRequest('bucket-123');

    expect($request)->toBeInstanceOf(Paginatable::class);
});

it('includes relationships in default query', function () {
    $request = new ListBucketKeysRequest('bucket-123');

    expect($request->query()->all())->toBe([
        'include' => 'filesystem',
    ]);
});

it('lists bucket keys and returns BucketKeyData collection', function () {
    Saloon::fake([
        ListBucketsRequest::class => new LaravelCloudFixture('buckets/list'),
    ]);

    $connector = new LaravelCloudConnector(config('laravel-cloud-sdk.token'));
    $listResponse = $connector->send(new ListBucketsRequest);
    $firstBucket = $listResponse->dtoOrFail()[0];

    Saloon::fake([
        ListBucketKeysRequest::class => new LaravelCloudFixture('bucket-keys/list'),
    ]);

    $response = $connector->send(new ListBucketKeysRequest($firstBucket->id));

    Saloon::assertSent(ListBucketKeysRequest::class);

    $dto = $response->dtoOrFail();
    expect($dto)->toBeArray();
    expect($dto[0])->toBeInstanceOf(BucketKeyData::class);
});
