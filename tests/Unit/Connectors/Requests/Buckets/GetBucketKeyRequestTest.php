<?php

use App\Data\LaravelCloud\Buckets\BucketKeyData;
use App\Http\Integrations\LaravelCloud\LaravelCloudConnector;
use App\Http\Integrations\LaravelCloud\Requests\Buckets\GetBucketKeyRequest;
use App\Http\Integrations\LaravelCloud\Requests\Buckets\ListBucketKeysRequest;
use App\Http\Integrations\LaravelCloud\Requests\Buckets\ListBucketsRequest;
use Saloon\Enums\Method;
use Saloon\Laravel\Facades\Saloon;
use Tests\Fixtures\LaravelCloud\LaravelCloudFixture;

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

    $connector = new LaravelCloudConnector(config('laravel-cloud.token'));
    $firstBucket = $connector->send(new ListBucketsRequest)->dtoOrFail()->first();

    Saloon::fake([
        ListBucketKeysRequest::class => new LaravelCloudFixture('bucket-keys/list'),
    ]);

    $firstKey = $connector->send(new ListBucketKeysRequest($firstBucket->id))->dtoOrFail()->first();

    Saloon::fake([
        GetBucketKeyRequest::class => new LaravelCloudFixture('bucket-keys/get'),
    ]);

    $response = $connector->send(new GetBucketKeyRequest($firstKey->id));

    Saloon::assertSent(GetBucketKeyRequest::class);

    $dto = $response->dtoOrFail();
    expect($dto)->toBeInstanceOf(BucketKeyData::class);
});
