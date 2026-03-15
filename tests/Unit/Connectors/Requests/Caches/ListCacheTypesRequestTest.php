<?php

use App\Data\LaravelCloud\Caches\CacheTypeData;
use App\Http\Integrations\LaravelCloud\LaravelCloudConnector;
use App\Http\Integrations\LaravelCloud\Requests\Caches\ListCacheTypesRequest;
use Illuminate\Support\Collection;
use Saloon\Enums\Method;
use Saloon\Laravel\Facades\Saloon;
use Tests\Fixtures\LaravelCloud\LaravelCloudFixture;

it('resolves the endpoint correctly', function () {
    $request = new ListCacheTypesRequest;

    expect($request->resolveEndpoint())->toBe('/caches/types');
});

it('has the correct HTTP method', function () {
    $request = new ListCacheTypesRequest;

    expect($request->getMethod())->toBe(Method::GET);
});

it('lists cache types and returns CacheTypeData collection', function () {
    Saloon::fake([
        ListCacheTypesRequest::class => new LaravelCloudFixture('caches/types'),
    ]);

    $connector = new LaravelCloudConnector(config('laravel-cloud.token'));
    $response = $connector->send(new ListCacheTypesRequest);

    Saloon::assertSent(ListCacheTypesRequest::class);
    expect($response->getPsrRequest()->getUri()->getPath())->toBe('/api/caches/types');

    $dto = $response->dtoOrFail();
    expect($dto)->toBeInstanceOf(Collection::class);
    expect($dto->first())->toBeInstanceOf(CacheTypeData::class);
    expect($dto->first()->type)->toBeString();
    expect($dto->first()->label)->toBeString();
    expect($dto->first()->regions)->toBeArray();
    expect($dto->first()->sizes)->toBeArray();
});
