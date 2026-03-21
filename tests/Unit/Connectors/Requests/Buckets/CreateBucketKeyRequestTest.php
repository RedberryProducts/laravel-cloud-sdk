<?php

use App\Data\LaravelCloud\Buckets\BucketKeyData;
use App\Data\LaravelCloud\Buckets\CreateBucketKeyData;
use App\Enums\LaravelCloud\KeyPermission;
use App\Http\Integrations\LaravelCloud\LaravelCloudConnector;
use App\Http\Integrations\LaravelCloud\Requests\Buckets\CreateBucketKeyRequest;
use App\Http\Integrations\LaravelCloud\Requests\Buckets\ListBucketsRequest;
use Saloon\Enums\Method;
use Saloon\Laravel\Facades\Saloon;
use Tests\Fixtures\LaravelCloud\LaravelCloudFixture;

it('resolves the endpoint correctly', function () {
    $data = new CreateBucketKeyData(
        name: 'new-key',
        permission: KeyPermission::READ_WRITE,
    );
    $request = new CreateBucketKeyRequest('bucket-123', $data);

    expect($request->resolveEndpoint())->toBe('/buckets/bucket-123/keys');
});

it('has the correct HTTP method', function () {
    $data = new CreateBucketKeyData(
        name: 'new-key',
        permission: KeyPermission::READ_WRITE,
    );
    $request = new CreateBucketKeyRequest('bucket-123', $data);

    expect($request->getMethod())->toBe(Method::POST);
});

it('sends correct body', function () {
    $data = new CreateBucketKeyData(
        name: 'new-key',
        permission: KeyPermission::READ_WRITE,
    );
    $request = new CreateBucketKeyRequest('bucket-123', $data);
    $body = $request->body()->all();

    expect($body['name'])->toBe('new-key');
    expect($body['permission'])->toBe('read_write');
});

it('creates a bucket key and returns BucketKeyData with all fields', function () {
    Saloon::fake([
        ListBucketsRequest::class => new LaravelCloudFixture('buckets/list'),
    ]);

    $connector = new LaravelCloudConnector(config('laravel-cloud.token'));
    $listResponse = $connector->send(new ListBucketsRequest);
    $firstBucket = $listResponse->dtoOrFail()->first();

    Saloon::fake([
        CreateBucketKeyRequest::class => new LaravelCloudFixture('bucket-keys/create'),
    ]);

    $data = new CreateBucketKeyData(
        name: 'new-key',
        permission: KeyPermission::READ_WRITE,
    );
    $response = $connector->send(new CreateBucketKeyRequest($firstBucket->id, $data));

    Saloon::assertSent(CreateBucketKeyRequest::class);

    $dto = $response->dtoOrFail();
    expect($dto)->toBeInstanceOf(BucketKeyData::class);
    expect($dto->id)->toBe('flsk-a14e1a83-a4d4-49a4-bdd9-93ae07a6a9cd');
    expect($dto->name)->toBe('new-key');
    expect($dto->permission)->toBe(KeyPermission::READ_WRITE);
    expect($dto->accessKeyId)->toBeString();
    expect($dto->accessKeySecret)->toBeString();
    expect($dto->createdAt)->not->toBeNull();
});
