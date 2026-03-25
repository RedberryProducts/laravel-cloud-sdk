<?php

use Illuminate\Support\Collection;
use Redberry\LaravelCloudSdk\Connectors\LaravelCloudConnector;
use Redberry\LaravelCloudSdk\Data\DatabaseClusters\DatabaseTypeData;
use Redberry\LaravelCloudSdk\Requests\DatabaseClusters\ListDatabaseTypesRequest;
use Redberry\LaravelCloudSdk\Tests\Fixtures\LaravelCloudFixture;
use Saloon\Enums\Method;
use Saloon\Laravel\Facades\Saloon;

it('resolves the endpoint correctly', function () {
    $request = new ListDatabaseTypesRequest;

    expect($request->resolveEndpoint())->toBe('/databases/types');
});

it('has the correct HTTP method', function () {
    $request = new ListDatabaseTypesRequest;

    expect($request->getMethod())->toBe(Method::GET);
});

it('lists database types and returns DatabaseTypeData collection', function () {
    Saloon::fake([
        ListDatabaseTypesRequest::class => new LaravelCloudFixture('database-clusters/types'),
    ]);

    $connector = new LaravelCloudConnector(config('laravel-cloud-sdk.token'));
    $response = $connector->send(new ListDatabaseTypesRequest);

    Saloon::assertSent(ListDatabaseTypesRequest::class);
    expect($response->getPsrRequest()->getUri()->getPath())->toBe('/api/databases/types');

    $dto = $response->dtoOrFail();
    expect($dto)->toBeInstanceOf(Collection::class);
    expect($dto)->not->toBeEmpty();
    expect($dto->first())->toBeInstanceOf(DatabaseTypeData::class);
});
