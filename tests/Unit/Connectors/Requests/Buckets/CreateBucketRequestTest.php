<?php

use App\Data\LaravelCloud\Buckets\BucketData;
use App\Data\LaravelCloud\Buckets\CreateBucketData;
use App\Enums\LaravelCloud\BucketJurisdiction;
use App\Enums\LaravelCloud\BucketStatus;
use App\Enums\LaravelCloud\BucketType;
use App\Enums\LaravelCloud\BucketVisibility;
use App\Enums\LaravelCloud\KeyPermission;
use App\Http\Integrations\LaravelCloud\LaravelCloudConnector;
use App\Http\Integrations\LaravelCloud\Requests\Buckets\CreateBucketRequest;
use Saloon\Enums\Method;
use Saloon\Laravel\Facades\Saloon;
use Tests\Fixtures\LaravelCloud\LaravelCloudFixture;

it('resolves the endpoint correctly', function () {
    $data = new CreateBucketData(
        name: 'test-bucket',
        visibility: BucketVisibility::PRIVATE,
        jurisdiction: BucketJurisdiction::DEFAULT,
        keyName: 'default-key',
        keyPermission: KeyPermission::READ_WRITE,
    );
    $request = new CreateBucketRequest($data);

    expect($request->resolveEndpoint())->toBe('/buckets');
});

it('has the correct HTTP method', function () {
    $data = new CreateBucketData(
        name: 'test-bucket',
        visibility: BucketVisibility::PRIVATE,
        jurisdiction: BucketJurisdiction::DEFAULT,
        keyName: 'default-key',
        keyPermission: KeyPermission::READ_WRITE,
    );
    $request = new CreateBucketRequest($data);

    expect($request->getMethod())->toBe(Method::POST);
});

it('sends correct body', function () {
    $data = new CreateBucketData(
        name: 'test-bucket',
        visibility: BucketVisibility::PRIVATE,
        jurisdiction: BucketJurisdiction::DEFAULT,
        keyName: 'default-key',
        keyPermission: KeyPermission::READ_WRITE,
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
        visibility: BucketVisibility::PRIVATE,
        jurisdiction: BucketJurisdiction::DEFAULT,
        keyName: 'default-key',
        keyPermission: KeyPermission::READ_WRITE,
    );

    $connector = new LaravelCloudConnector(config('laravel-cloud.token'));
    $response = $connector->send(new CreateBucketRequest($data));

    Saloon::assertSent(CreateBucketRequest::class);

    $dto = $response->dtoOrFail();
    expect($dto)->toBeInstanceOf(BucketData::class);
    expect($dto->id)->toBe('fls-a14e19d6-8db3-47fe-96fb-343e55774021');
    expect($dto->name)->toBe('test-bucket');
    expect($dto->type)->toBe(BucketType::CLOUDFLARE_R2);
    expect($dto->status)->toBe(BucketStatus::AVAILABLE);
    expect($dto->visibility)->toBe(BucketVisibility::PRIVATE);
    expect($dto->jurisdiction)->toBe(BucketJurisdiction::DEFAULT);
    expect($dto->endpoint)->toBeString();
    expect($dto->url)->toBeNull();
    expect($dto->allowedOrigins)->toBeNull();
    expect($dto->createdAt)->not->toBeNull();
});
