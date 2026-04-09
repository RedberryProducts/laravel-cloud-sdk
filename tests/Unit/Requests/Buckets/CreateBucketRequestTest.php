<?php

use Redberry\LaravelCloudSdk\Connectors\LaravelCloudConnector;
use Redberry\LaravelCloudSdk\Data\Buckets\BucketData;
use Redberry\LaravelCloudSdk\Data\Buckets\CreateBucketData;
use Redberry\LaravelCloudSdk\Enums\BucketJurisdiction;
use Redberry\LaravelCloudSdk\Enums\BucketStatus;
use Redberry\LaravelCloudSdk\Enums\BucketType;
use Redberry\LaravelCloudSdk\Enums\BucketVisibility;
use Redberry\LaravelCloudSdk\Enums\KeyPermission;
use Redberry\LaravelCloudSdk\Requests\Buckets\CreateBucketRequest;
use Redberry\LaravelCloudSdk\Tests\Fixtures\LaravelCloudFixture;
use Saloon\Enums\Method;
use Saloon\Laravel\Facades\Saloon;

it('resolves the endpoint correctly', function () {
    $data = new CreateBucketData(
        name: 'test-bucket',
        visibility: BucketVisibility::Private,
        jurisdiction: BucketJurisdiction::Default,
        keyName: 'default-key',
        keyPermission: KeyPermission::ReadWrite,
    );
    $request = new CreateBucketRequest($data);

    expect($request->resolveEndpoint())->toBe('/buckets');
});

it('has the correct HTTP method', function () {
    $data = new CreateBucketData(
        name: 'test-bucket',
        visibility: BucketVisibility::Private,
        jurisdiction: BucketJurisdiction::Default,
        keyName: 'default-key',
        keyPermission: KeyPermission::ReadWrite,
    );
    $request = new CreateBucketRequest($data);

    expect($request->getMethod())->toBe(Method::POST);
});

it('sends correct body', function () {
    $data = new CreateBucketData(
        name: 'test-bucket',
        visibility: BucketVisibility::Private,
        jurisdiction: BucketJurisdiction::Default,
        keyName: 'default-key',
        keyPermission: KeyPermission::ReadWrite,
    );
    $request = new CreateBucketRequest($data);
    $body = $request->body()->all();

    expect($body['name'])->toBe('test-bucket');
    expect($body['visibility'])->toBe('private');
    expect($body['jurisdiction'])->toBe('default');
    expect($body['key_name'])->toBe('default-key');
    expect($body['key_permission'])->toBe('read_write');
});

it('creates a bucket and returns BucketData with all fields', function () {
    Saloon::fake([
        CreateBucketRequest::class => new LaravelCloudFixture('buckets/create'),
    ]);

    $data = new CreateBucketData(
        name: 'test-bucket',
        visibility: BucketVisibility::Private,
        jurisdiction: BucketJurisdiction::Default,
        keyName: 'default-key',
        keyPermission: KeyPermission::ReadWrite,
    );

    $connector = new LaravelCloudConnector(config('laravel-cloud-sdk.token'));
    $response = $connector->send(new CreateBucketRequest($data));

    Saloon::assertSent(CreateBucketRequest::class);

    $dto = $response->dtoOrFail();
    expect($dto)->toBeInstanceOf(BucketData::class);
    expect($dto->id)->toBe('fls-a14e19d6-8db3-47fe-96fb-343e55774021');
    expect($dto->name)->toBe('test-bucket');
    expect($dto->type)->toBe(BucketType::CloudflareR2);
    expect($dto->status)->toBe(BucketStatus::Available);
    expect($dto->visibility)->toBe(BucketVisibility::Private);
    expect($dto->jurisdiction)->toBe(BucketJurisdiction::Default);
    expect($dto->endpoint)->toBeString();
    expect($dto->url)->toBeNull();
    expect($dto->allowedOrigins)->toBeNull();
    expect($dto->createdAt)->not->toBeNull();
});
