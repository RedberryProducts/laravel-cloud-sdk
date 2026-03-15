<?php

use App\Data\LaravelCloud\Buckets\BucketData;
use App\Data\LaravelCloud\Buckets\UpdateBucketData;
use App\Http\Integrations\LaravelCloud\LaravelCloudConnector;
use App\Http\Integrations\LaravelCloud\Requests\Buckets\ListBucketsRequest;
use App\Http\Integrations\LaravelCloud\Requests\Buckets\UpdateBucketRequest;
use Saloon\Enums\Method;
use Saloon\Laravel\Facades\Saloon;
use Tests\Fixtures\LaravelCloud\LaravelCloudFixture;

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

it('sends correct body', function () {
    $data = new UpdateBucketData(name: 'updated-bucket');
    $request = new UpdateBucketRequest('bucket-123', $data);
    $body = $request->body()->all();

    expect($body['name'])->toBe('updated-bucket');
});

it('updates a bucket and returns BucketData', function () {
    Saloon::fake([
        ListBucketsRequest::class => new LaravelCloudFixture('buckets/list'),
    ]);

    $connector = new LaravelCloudConnector(config('laravel-cloud.token'));
    $listResponse = $connector->send(new ListBucketsRequest);
    $firstBucket = $listResponse->dtoOrFail()->first();

    Saloon::fake([
        UpdateBucketRequest::class => new LaravelCloudFixture('buckets/update'),
    ]);

    $data = new UpdateBucketData(name: 'updated-bucket');
    $response = $connector->send(new UpdateBucketRequest($firstBucket->id, $data));

    Saloon::assertSent(UpdateBucketRequest::class);

    $dto = $response->dtoOrFail();
    expect($dto)->toBeInstanceOf(BucketData::class);
});
