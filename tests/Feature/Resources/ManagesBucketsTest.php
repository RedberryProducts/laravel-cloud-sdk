<?php

use Illuminate\Support\LazyCollection;
use Redberry\LaravelCloudSdk\Data\Buckets\BucketData;
use Redberry\LaravelCloudSdk\Data\Buckets\BucketKeyData;
use Redberry\LaravelCloudSdk\Data\Buckets\CreateBucketData;
use Redberry\LaravelCloudSdk\Data\Buckets\UpdateBucketData;
use Redberry\LaravelCloudSdk\Enums\BucketJurisdiction;
use Redberry\LaravelCloudSdk\Enums\BucketVisibility;
use Redberry\LaravelCloudSdk\Enums\KeyPermission;
use Redberry\LaravelCloudSdk\LaravelCloud;
use Redberry\LaravelCloudSdk\Requests\Buckets\CreateBucketRequest;
use Redberry\LaravelCloudSdk\Requests\Buckets\DeleteBucketRequest;
use Redberry\LaravelCloudSdk\Requests\Buckets\GetBucketRequest;
use Redberry\LaravelCloudSdk\Requests\Buckets\ListBucketsRequest;
use Redberry\LaravelCloudSdk\Requests\Buckets\UpdateBucketRequest;
use Redberry\LaravelCloudSdk\Tests\Fixtures\LaravelCloudFixture;
use Saloon\Laravel\Facades\Saloon;

it('lists buckets', function () {
    Saloon::fake([
        ListBucketsRequest::class => new LaravelCloudFixture('buckets/list'),
    ]);

    $result = (new LaravelCloud('token'))->buckets();

    expect($result)->toBeInstanceOf(LazyCollection::class);

    $first = $result->first();
    expect($first)->toBeInstanceOf(BucketData::class);
    expect($first->keys)->toHaveCount(2);
    expect($first->keys)->each->toBeInstanceOf(BucketKeyData::class);
    Saloon::assertSent(ListBucketsRequest::class);
});

it('retrieves a single bucket by id', function () {
    Saloon::fake([
        GetBucketRequest::class => new LaravelCloudFixture('buckets/get'),
    ]);

    $result = (new LaravelCloud('token'))->bucket('fls-a14e19d6-8db3-47fe-96fb-343e55774021');

    Saloon::assertSent(GetBucketRequest::class);
    expect($result)->toBeInstanceOf(BucketData::class);
    expect($result->id)->toBe('fls-a14e19d6-8db3-47fe-96fb-343e55774021');
    expect($result->name)->toBe('updated-bucket');
    expect($result->keys)->toHaveCount(2);
    expect($result->keys)->each->toBeInstanceOf(BucketKeyData::class);
});

it('creates a bucket with named params', function () {
    Saloon::fake([
        CreateBucketRequest::class => new LaravelCloudFixture('buckets/create'),
    ]);

    $result = (new LaravelCloud('token'))->createBucket(
        name: 'media-storage',
        visibility: BucketVisibility::Private,
        jurisdiction: BucketJurisdiction::Default,
        keyName: 'default-key',
        keyPermission: KeyPermission::ReadWrite,
    );

    Saloon::assertSent(CreateBucketRequest::class);
    expect($result)->toBeInstanceOf(BucketData::class);
});

it('creates a bucket with string enums', function () {
    Saloon::fake([
        CreateBucketRequest::class => new LaravelCloudFixture('buckets/create'),
    ]);

    $result = (new LaravelCloud('token'))->createBucket(
        name: 'media-storage',
        visibility: 'private',
        jurisdiction: 'default',
        keyName: 'default-key',
        keyPermission: 'read_write',
    );

    Saloon::assertSent(CreateBucketRequest::class);
    expect($result)->toBeInstanceOf(BucketData::class);
});

it('creates a bucket via createBucketWith()', function () {
    Saloon::fake([
        CreateBucketRequest::class => new LaravelCloudFixture('buckets/create'),
    ]);

    $result = (new LaravelCloud('token'))->createBucketWith(
        new CreateBucketData(
            name: 'media-storage',
            visibility: BucketVisibility::Private,
            jurisdiction: BucketJurisdiction::Default,
            keyName: 'default-key',
            keyPermission: KeyPermission::ReadWrite,
        )
    );

    Saloon::assertSent(CreateBucketRequest::class);
    expect($result)->toBeInstanceOf(BucketData::class);
});

it('updates a bucket with named params', function () {
    Saloon::fake([
        UpdateBucketRequest::class => new LaravelCloudFixture('buckets/update'),
    ]);

    $result = (new LaravelCloud('token'))->updateBucket(
        'fls-a14e19d6-8db3-47fe-96fb-343e55774021',
        name: 'test-bucket',
    );

    Saloon::assertSent(UpdateBucketRequest::class);
    expect($result)->toBeInstanceOf(BucketData::class);
});

it('updates a bucket via updateBucketWith()', function () {
    Saloon::fake([
        UpdateBucketRequest::class => new LaravelCloudFixture('buckets/update'),
    ]);

    $result = (new LaravelCloud('token'))->updateBucketWith(
        'fls-a14e19d6-8db3-47fe-96fb-343e55774021',
        new UpdateBucketData(name: 'test-bucket'),
    );

    Saloon::assertSent(UpdateBucketRequest::class);
    expect($result)->toBeInstanceOf(BucketData::class);
});

it('deletes a bucket', function () {
    Saloon::fake([
        DeleteBucketRequest::class => new LaravelCloudFixture('buckets/delete'),
    ]);

    (new LaravelCloud('token'))->deleteBucket('fls-a14e19d6-8db3-47fe-96fb-343e55774021');

    Saloon::assertSent(DeleteBucketRequest::class);
});
