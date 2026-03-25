<?php

use Redberry\LaravelCloudSdk\Connectors\LaravelCloudConnector;
use Redberry\LaravelCloudSdk\Data\Caches\CacheConnectionData;
use Redberry\LaravelCloudSdk\Data\Caches\CacheData;
use Redberry\LaravelCloudSdk\Data\Caches\CreateCacheData;
use Redberry\LaravelCloudSdk\Enums\CacheProtocol;
use Redberry\LaravelCloudSdk\Enums\CacheSize;
use Redberry\LaravelCloudSdk\Enums\CacheStatus;
use Redberry\LaravelCloudSdk\Enums\CacheType;
use Redberry\LaravelCloudSdk\Enums\CloudRegion;
use Redberry\LaravelCloudSdk\Requests\Caches\CreateCacheRequest;
use Redberry\LaravelCloudSdk\Tests\Fixtures\LaravelCloudFixture;
use Saloon\Enums\Method;
use Saloon\Laravel\Facades\Saloon;

it('resolves the endpoint correctly', function () {
    $data = new CreateCacheData(
        type: CacheType::LARAVEL_VALKEY,
        name: 'test-cache-two',
        region: CloudRegion::US_EAST_1,
        size: CacheSize::VALKEY_PRO_250MB,
        autoUpgradeEnabled: true,
        isPublic: false,
    );
    $request = new CreateCacheRequest($data);

    expect($request->resolveEndpoint())->toBe('/caches');
});

it('has the correct HTTP method', function () {
    $data = new CreateCacheData(
        type: CacheType::LARAVEL_VALKEY,
        name: 'test-cache-two',
        region: CloudRegion::US_EAST_1,
        size: CacheSize::VALKEY_PRO_250MB,
        autoUpgradeEnabled: true,
        isPublic: false,
    );
    $request = new CreateCacheRequest($data);

    expect($request->getMethod())->toBe(Method::POST);
});

it('sends correct body', function () {
    $data = new CreateCacheData(
        type: CacheType::LARAVEL_VALKEY,
        name: 'test-cache-two',
        region: CloudRegion::US_EAST_1,
        size: CacheSize::VALKEY_PRO_250MB,
        autoUpgradeEnabled: true,
        isPublic: false,
    );
    $request = new CreateCacheRequest($data);
    $body = $request->body()->all();

    expect($body['type'])->toBe('laravel_valkey');
    expect($body['name'])->toBe('test-cache-two');
    expect($body['region'])->toBe('us-east-1');
    expect($body['size'])->toBe('valkey-pro.250mb');
    expect($body['auto_upgrade_enabled'])->toBeTrue();
    expect($body['is_public'])->toBeFalse();
});

it('creates a cache and returns CacheData with all fields', function () {
    Saloon::fake([
        CreateCacheRequest::class => new LaravelCloudFixture('caches/create'),
    ]);

    $data = new CreateCacheData(
        type: CacheType::LARAVEL_VALKEY,
        name: 'test-cache-two',
        region: CloudRegion::US_EAST_1,
        size: CacheSize::VALKEY_PRO_250MB,
        autoUpgradeEnabled: true,
        isPublic: false,
    );

    $connector = new LaravelCloudConnector(config('laravel-cloud-sdk.token'));
    $response = $connector->send(new CreateCacheRequest($data));

    Saloon::assertSent(CreateCacheRequest::class);

    $dto = $response->dtoOrFail();
    expect($dto)->toBeInstanceOf(CacheData::class);
    expect($dto->id)->toBe('cache-a14df9ab-a7a4-4ae1-8e86-c1d29574740d');
    expect($dto->name)->toBe('test-cache-two');
    expect($dto->type)->toBe(CacheType::LARAVEL_VALKEY);
    expect($dto->status)->toBe(CacheStatus::CREATING);
    expect($dto->region)->toBe(CloudRegion::US_EAST_1);
    expect($dto->size)->toBe(CacheSize::VALKEY_PRO_250MB);
    expect($dto->autoUpgradeEnabled)->toBeTrue();
    expect($dto->isPublic)->toBeFalse();
    expect($dto->connection)->toBeInstanceOf(CacheConnectionData::class);
    expect($dto->connection->hostname)->toBeString();
    expect($dto->connection->port)->toBeNull();
    expect($dto->connection->protocol)->toBe(CacheProtocol::REDIS);
    expect($dto->connection->username)->toBeString();
    expect($dto->connection->password)->toBeString();
    expect($dto->createdAt)->not->toBeNull();
});
