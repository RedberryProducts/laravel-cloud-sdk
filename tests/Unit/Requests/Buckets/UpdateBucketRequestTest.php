<?php

use Redberry\LaravelCloudSdk\Connectors\LaravelCloudConnector;
use Redberry\LaravelCloudSdk\Data\Buckets\BucketData;
use Redberry\LaravelCloudSdk\Data\Buckets\UpdateBucketData;
use Redberry\LaravelCloudSdk\Enums\BucketJurisdiction;
use Redberry\LaravelCloudSdk\Enums\BucketStatus;
use Redberry\LaravelCloudSdk\Enums\BucketType;
use Redberry\LaravelCloudSdk\Enums\BucketVisibility;
use Redberry\LaravelCloudSdk\Requests\Buckets\ListBucketsRequest;
use Redberry\LaravelCloudSdk\Requests\Buckets\UpdateBucketRequest;
use Redberry\LaravelCloudSdk\Tests\Fixtures\LaravelCloudFixture;
use Saloon\Enums\Method;
use Saloon\Laravel\Facades\Saloon;

it('resolves the endpoint correctly', function () {
    $data = new UpdateBucketData(name: 'updated-bucket');
    $request = new UpdateBucketRequest('bucket-123', $data);

    expect($request->resolveEndpoint())->toBe('/buckets/bucket-123');
});

it('has the correct HTTP method', function () {
    $data = new UpdateBucketData(name: 'updated-bucket');
    $request = new UpdateBucketRequest('bucket-123', $data);

    expect($request->getMethod())->toBe(Method::PATCH);
});

it('sends correct body with all optional fields', function () {
    $data = new UpdateBucketData(
        name: 'updated-bucket',
        visibility: BucketVisibility::PUBLIC,
        allowedOrigins: ['https://example.com'],
    );
    $request = new UpdateBucketRequest('bucket-123', $data);
    $body = $request->body()->all();

    expect($body['name'])->toBe('updated-bucket');
    expect($body['visibility'])->toBe('public');
    expect($body['allowed_origins'])->toBe(['https://example.com']);
});

it('excludes unset optional fields from body', function () {
    $data = new UpdateBucketData(name: 'updated-bucket');
    $request = new UpdateBucketRequest('bucket-123', $data);
    $body = $request->body()->all();

    expect($body)->toHaveKey('name');
    expect($body)->not->toHaveKey('visibility');
    expect($body)->not->toHaveKey('allowed_origins');
});

it('updates a bucket and returns BucketData with all fields', function () {
    Saloon::fake([
        ListBucketsRequest::class => new LaravelCloudFixture('buckets/list'),
    ]);

    $connector = new LaravelCloudConnector(config('laravel-cloud-sdk.token'));
    $listResponse = $connector->send(new ListBucketsRequest);
    $firstBucket = $listResponse->dtoOrFail()[0];

    Saloon::fake([
        UpdateBucketRequest::class => new LaravelCloudFixture('buckets/update'),
    ]);

    $data = new UpdateBucketData(name: 'updated-bucket');
    $response = $connector->send(new UpdateBucketRequest($firstBucket->id, $data));

    Saloon::assertSent(UpdateBucketRequest::class);

    $dto = $response->dtoOrFail();
    expect($dto)->toBeInstanceOf(BucketData::class);
    expect($dto->id)->toBe('fls-a14e19d6-8db3-47fe-96fb-343e55774021');
    expect($dto->name)->toBe('updated-bucket');
    expect($dto->type)->toBe(BucketType::CLOUDFLARE_R2);
    expect($dto->status)->toBe(BucketStatus::AVAILABLE);
    expect($dto->visibility)->toBe(BucketVisibility::PRIVATE);
    expect($dto->jurisdiction)->toBe(BucketJurisdiction::DEFAULT);
    expect($dto->endpoint)->toBeString();
    expect($dto->url)->toBeNull();
    expect($dto->allowedOrigins)->toBeNull();
    expect($dto->createdAt)->not->toBeNull();
});
