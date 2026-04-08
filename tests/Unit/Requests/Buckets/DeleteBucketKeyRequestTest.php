<?php

use Redberry\LaravelCloudSdk\Connectors\LaravelCloudConnector;
use Redberry\LaravelCloudSdk\Data\Buckets\CreateBucketKeyData;
use Redberry\LaravelCloudSdk\Enums\KeyPermission;
use Redberry\LaravelCloudSdk\Requests\Buckets\CreateBucketKeyRequest;
use Redberry\LaravelCloudSdk\Requests\Buckets\DeleteBucketKeyRequest;
use Redberry\LaravelCloudSdk\Requests\Buckets\ListBucketsRequest;
use Redberry\LaravelCloudSdk\Tests\Fixtures\LaravelCloudFixture;
use Saloon\Enums\Method;
use Saloon\Laravel\Facades\Saloon;

it('resolves the endpoint correctly', function () {
    $request = new DeleteBucketKeyRequest('key-123');

    expect($request->resolveEndpoint())->toBe('/bucket-keys/key-123');
});

it('has the correct HTTP method', function () {
    $request = new DeleteBucketKeyRequest('key-123');

    expect($request->getMethod())->toBe(Method::DELETE);
});

it('sends the delete request successfully', function () {
    Saloon::fake([
        ListBucketsRequest::class => new LaravelCloudFixture('buckets/list'),
        CreateBucketKeyRequest::class => new LaravelCloudFixture('buckets/delete-create-key'),
        DeleteBucketKeyRequest::class => new LaravelCloudFixture('buckets/delete-key'),
    ]);

    $connector = new LaravelCloudConnector(config('laravel-cloud-sdk.token'));
    $firstBucket = $connector->send(new ListBucketsRequest)->dtoOrFail()[0];

    $key = $connector->send(new CreateBucketKeyRequest($firstBucket->id, new CreateBucketKeyData(
        name: 'sdk-delete-test',
        permission: KeyPermission::READ_WRITE,
    )))->dtoOrFail();

    $response = $connector->send(new DeleteBucketKeyRequest($key->id));

    Saloon::assertSent(DeleteBucketKeyRequest::class);
    expect($response->successful())->toBeTrue();
});
