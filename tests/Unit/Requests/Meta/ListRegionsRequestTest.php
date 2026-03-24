<?php

use Redberry\LaravelCloudSdk\Data\Meta\RegionData;
use Redberry\LaravelCloudSdk\Enums\CloudRegion;
use Redberry\LaravelCloudSdk\Connectors\LaravelCloudConnector;
use Redberry\LaravelCloudSdk\Requests\Meta\ListRegionsRequest;
use Illuminate\Support\Collection;
use Saloon\Enums\Method;
use Saloon\Laravel\Facades\Saloon;
use Redberry\LaravelCloudSdk\Tests\Fixtures\LaravelCloudFixture;

it('resolves the endpoint correctly', function () {
    $request = new ListRegionsRequest;

    expect($request->resolveEndpoint())->toBe('/meta/regions');
});

it('has the correct HTTP method', function () {
    $request = new ListRegionsRequest;

    expect($request->getMethod())->toBe(Method::GET);
});

it('lists regions and returns RegionData collection', function () {
    Saloon::fake([
        ListRegionsRequest::class => new LaravelCloudFixture('meta/list-regions'),
    ]);

    $connector = new LaravelCloudConnector(config('laravel-cloud-sdk.token'));
    $response = $connector->send(new ListRegionsRequest);

    Saloon::assertSent(ListRegionsRequest::class);
    expect($response->getPsrRequest()->getMethod())->toBe('GET');
    expect($response->getPsrRequest()->getUri()->getPath())->toBe('/api/meta/regions');

    $dto = $response->dtoOrFail();
    expect($dto)->toBeInstanceOf(Collection::class);
    expect($dto->first())->toBeInstanceOf(RegionData::class);
    expect($dto->first()->region)->toBeInstanceOf(CloudRegion::class);
    expect($dto->first()->label)->toBeString();
    expect($dto->first()->flag)->toBeString();
});
