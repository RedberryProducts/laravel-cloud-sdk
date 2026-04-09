<?php

use Illuminate\Support\LazyCollection;
use Redberry\LaravelCloudSdk\Data\Buckets\BucketKeyData;
use Redberry\LaravelCloudSdk\Data\Buckets\CreateBucketKeyData;
use Redberry\LaravelCloudSdk\Data\Buckets\UpdateBucketKeyData;
use Redberry\LaravelCloudSdk\Enums\KeyPermission;
use Redberry\LaravelCloudSdk\LaravelCloud;
use Redberry\LaravelCloudSdk\Requests\Buckets\CreateBucketKeyRequest;
use Redberry\LaravelCloudSdk\Requests\Buckets\DeleteBucketKeyRequest;
use Redberry\LaravelCloudSdk\Requests\Buckets\GetBucketKeyRequest;
use Redberry\LaravelCloudSdk\Requests\Buckets\ListBucketKeysRequest;
use Redberry\LaravelCloudSdk\Requests\Buckets\UpdateBucketKeyRequest;
use Redberry\LaravelCloudSdk\Tests\Fixtures\LaravelCloudFixture;
use Saloon\Laravel\Facades\Saloon;

it('lists bucket keys for a bucket', function () {
    Saloon::fake([
        ListBucketKeysRequest::class => new LaravelCloudFixture('bucket-keys/list'),
    ]);

    $result = (new LaravelCloud('token'))->bucketKeys('fls-a14e19d6-8db3-47fe-96fb-343e55774021');

    expect($result)->toBeInstanceOf(LazyCollection::class);
    expect($result->first())->toBeInstanceOf(BucketKeyData::class);
    Saloon::assertSent(ListBucketKeysRequest::class);
});

it('retrieves a single bucket key by id', function () {
    Saloon::fake([
        GetBucketKeyRequest::class => new LaravelCloudFixture('bucket-keys/get'),
    ]);

    $result = (new LaravelCloud('token'))->bucketKey('flsk-a14e19d9-bfec-488e-8ee5-79b029e9d974');

    Saloon::assertSent(GetBucketKeyRequest::class);
    expect($result)->toBeInstanceOf(BucketKeyData::class);
    expect($result->id)->toBe('flsk-a14e19d9-bfec-488e-8ee5-79b029e9d974');
    expect($result->name)->toBe('default-key');
    expect($result->permission)->toBe(KeyPermission::ReadWrite);
});

it('creates a bucket key with named params', function () {
    Saloon::fake([
        CreateBucketKeyRequest::class => new LaravelCloudFixture('bucket-keys/create'),
    ]);

    $result = (new LaravelCloud('token'))->createBucketKey(
        bucketId: 'fls-a14e19d6-8db3-47fe-96fb-343e55774021',
        name: 'upload-key',
        permission: KeyPermission::ReadWrite,
    );

    Saloon::assertSent(CreateBucketKeyRequest::class);
    expect($result)->toBeInstanceOf(BucketKeyData::class);
});

it('creates a bucket key with a string permission', function () {
    Saloon::fake([
        CreateBucketKeyRequest::class => new LaravelCloudFixture('bucket-keys/create'),
    ]);

    $result = (new LaravelCloud('token'))->createBucketKey(
        bucketId: 'fls-a14e19d6-8db3-47fe-96fb-343e55774021',
        name: 'upload-key',
        permission: 'read_write',
    );

    Saloon::assertSent(CreateBucketKeyRequest::class);
    expect($result)->toBeInstanceOf(BucketKeyData::class);
});

it('creates a bucket key via createBucketKeyWith()', function () {
    Saloon::fake([
        CreateBucketKeyRequest::class => new LaravelCloudFixture('bucket-keys/create'),
    ]);

    $result = (new LaravelCloud('token'))->createBucketKeyWith(
        'fls-a14e19d6-8db3-47fe-96fb-343e55774021',
        new CreateBucketKeyData(name: 'upload-key', permission: KeyPermission::ReadWrite),
    );

    Saloon::assertSent(CreateBucketKeyRequest::class);
    expect($result)->toBeInstanceOf(BucketKeyData::class);
});

it('updates a bucket key with a name', function () {
    Saloon::fake([
        UpdateBucketKeyRequest::class => new LaravelCloudFixture('bucket-keys/update'),
    ]);

    $result = (new LaravelCloud('token'))->updateBucketKey(
        'flsk-a14e19d9-bfec-488e-8ee5-79b029e9d974',
        'default-key',
    );

    Saloon::assertSent(UpdateBucketKeyRequest::class);
    expect($result)->toBeInstanceOf(BucketKeyData::class);
});

it('updates a bucket key via updateBucketKeyWith()', function () {
    Saloon::fake([
        UpdateBucketKeyRequest::class => new LaravelCloudFixture('bucket-keys/update'),
    ]);

    $result = (new LaravelCloud('token'))->updateBucketKeyWith(
        'flsk-a14e19d9-bfec-488e-8ee5-79b029e9d974',
        new UpdateBucketKeyData(name: 'default-key'),
    );

    Saloon::assertSent(UpdateBucketKeyRequest::class);
    expect($result)->toBeInstanceOf(BucketKeyData::class);
});

it('deletes a bucket key', function () {
    Saloon::fake([
        DeleteBucketKeyRequest::class => new LaravelCloudFixture('buckets/delete-key'),
    ]);

    (new LaravelCloud('token'))->deleteBucketKey('flsk-a14e1a83-a4d4-49a4-bdd9-93ae07a6a9cd');

    Saloon::assertSent(DeleteBucketKeyRequest::class);
});
