<?php

use App\Data\LaravelCloud\Buckets\BucketKeyData;
use App\Data\LaravelCloud\Buckets\UpdateBucketKeyData;
use App\Http\Integrations\LaravelCloud\LaravelCloudConnector;
use App\Http\Integrations\LaravelCloud\Requests\Buckets\ListBucketKeysRequest;
use App\Http\Integrations\LaravelCloud\Requests\Buckets\ListBucketsRequest;
use App\Http\Integrations\LaravelCloud\Requests\Buckets\UpdateBucketKeyRequest;
use Saloon\Enums\Method;
use Saloon\Laravel\Facades\Saloon;
use Tests\Fixtures\LaravelCloud\LaravelCloudFixture;

it('resolves the endpoint correctly', function () {
    $data = new UpdateBucketKeyData(name: 'updated-key');
    $request = new UpdateBucketKeyRequest('key-123', $data);

    expect($request->resolveEndpoint())->toBe('/bucket-keys/key-123');
});

it('has the correct HTTP method', function () {
    $data = new UpdateBucketKeyData(name: 'updated-key');
    $request = new UpdateBucketKeyRequest('key-123', $data);

    expect($request->getMethod())->toBe(Method::PATCH);
});

it('sends correct body', function () {
    $data = new UpdateBucketKeyData(name: 'updated-key');
    $request = new UpdateBucketKeyRequest('key-123', $data);
    $body = $request->body()->all();

    expect($body['name'])->toBe('updated-key');
});

it('updates a bucket key and returns BucketKeyData', function () {
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
        UpdateBucketKeyRequest::class => new LaravelCloudFixture('bucket-keys/update'),
    ]);

    $data = new UpdateBucketKeyData(name: 'updated-key');
    $response = $connector->send(new UpdateBucketKeyRequest($firstKey->id, $data));

    Saloon::assertSent(UpdateBucketKeyRequest::class);

    $dto = $response->dtoOrFail();
    expect($dto)->toBeInstanceOf(BucketKeyData::class);
});
