<?php

use Redberry\LaravelCloudSdk\Connectors\LaravelCloudConnector;
use Redberry\LaravelCloudSdk\Data\Buckets\BucketKeyData;
use Redberry\LaravelCloudSdk\Data\Buckets\CreateBucketData;
use Redberry\LaravelCloudSdk\Enums\BucketJurisdiction;
use Redberry\LaravelCloudSdk\Enums\BucketVisibility;
use Redberry\LaravelCloudSdk\Enums\KeyPermission;
use Redberry\LaravelCloudSdk\Requests\Buckets\CreateBucketRequest;
use Redberry\LaravelCloudSdk\Requests\Buckets\DeleteBucketKeyRequest;
use Redberry\LaravelCloudSdk\Requests\Buckets\DeleteBucketRequest;
use Redberry\LaravelCloudSdk\Requests\Buckets\ListBucketKeysRequest;
use Redberry\LaravelCloudSdk\Tests\Fixtures\LaravelCloudFixture;
use Saloon\Enums\Method;
use Saloon\Laravel\Facades\Saloon;

it('resolves the endpoint correctly', function () {
    $request = new DeleteBucketRequest('bucket-123');

    expect($request->resolveEndpoint())->toBe('/buckets/bucket-123');
});

it('has the correct HTTP method', function () {
    $request = new DeleteBucketRequest('bucket-123');

    expect($request->getMethod())->toBe(Method::DELETE);
});

it('sends the delete request successfully', function () {
    Saloon::fake([
        CreateBucketRequest::class => new LaravelCloudFixture('buckets/delete-create'),
        ListBucketKeysRequest::class => new LaravelCloudFixture('buckets/delete-list-keys'),
        DeleteBucketKeyRequest::class => new LaravelCloudFixture('buckets/delete-bucket-key'),
        DeleteBucketRequest::class => new LaravelCloudFixture('buckets/delete'),
    ]);

    $connector = new LaravelCloudConnector(config('laravel-cloud-sdk.token'));
    $bucket = $connector->send(new CreateBucketRequest(new CreateBucketData(
        name: 'sdk-delete-test',
        visibility: BucketVisibility::Private,
        jurisdiction: BucketJurisdiction::Default,
        keyName: 'sdk-delete-key',
        keyPermission: KeyPermission::ReadWrite,
    )))->dtoOrFail();

    $keys = $connector->paginate(new ListBucketKeysRequest($bucket->id))->collect();

    $keys->each(function (BucketKeyData $key) use ($connector) {
        $connector->send(new DeleteBucketKeyRequest($key->id));
    });

    $response = $connector->send(new DeleteBucketRequest($bucket->id));

    Saloon::assertSent(DeleteBucketRequest::class);
    expect($response->successful())->toBeTrue();
});
