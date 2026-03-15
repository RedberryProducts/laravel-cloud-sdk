<?php

use App\Data\LaravelCloud\Caches\CacheData;
use App\Data\LaravelCloud\Caches\CreateCacheData;
use App\Enums\LaravelCloud\CacheSize;
use App\Enums\LaravelCloud\CacheType;
use App\Enums\LaravelCloud\CloudRegion;
use App\Http\Integrations\LaravelCloud\LaravelCloudConnector;
use App\Http\Integrations\LaravelCloud\Requests\Caches\CreateCacheRequest;
use Saloon\Enums\Method;
use Saloon\Laravel\Facades\Saloon;
use Tests\Fixtures\LaravelCloud\LaravelCloudFixture;

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

it('creates a cache and returns CacheData', function () {
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

    $connector = new LaravelCloudConnector(config('laravel-cloud.token'));
    $response = $connector->send(new CreateCacheRequest($data));

    Saloon::assertSent(CreateCacheRequest::class);

    $dto = $response->dtoOrFail();
    expect($dto)->toBeInstanceOf(CacheData::class);
});
