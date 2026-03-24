<?php

use Redberry\LaravelCloudSdk\Data\Instances\InstanceSizeData;
use Redberry\LaravelCloudSdk\Connectors\LaravelCloudConnector;
use Redberry\LaravelCloudSdk\Requests\Instances\ListInstanceSizesRequest;
use Illuminate\Support\Collection;
use Saloon\Enums\Method;
use Saloon\Laravel\Facades\Saloon;
use Redberry\LaravelCloudSdk\Tests\Fixtures\LaravelCloudFixture;

it('resolves the endpoint correctly', function () {
    $request = new ListInstanceSizesRequest;

    expect($request->resolveEndpoint())->toBe('/instances/sizes');
});

it('has the correct HTTP method', function () {
    $request = new ListInstanceSizesRequest;

    expect($request->getMethod())->toBe(Method::GET);
});

it('lists instance sizes and returns InstanceSizeData collection', function () {
    Saloon::fake([
        ListInstanceSizesRequest::class => new LaravelCloudFixture('instances/sizes'),
    ]);

    $connector = new LaravelCloudConnector(config('laravel-cloud-sdk.token'));
    $response = $connector->send(new ListInstanceSizesRequest);

    Saloon::assertSent(ListInstanceSizesRequest::class);

    $dto = $response->dtoOrFail();
    expect($dto)->toBeInstanceOf(Collection::class);
    expect($dto->first())->toBeInstanceOf(InstanceSizeData::class);
});
