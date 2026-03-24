<?php

use Redberry\LaravelCloudSdk\Data\DatabaseClusters\DatabaseClusterData;
use Redberry\LaravelCloudSdk\Connectors\LaravelCloudConnector;
use Redberry\LaravelCloudSdk\Requests\DatabaseClusters\ListDatabaseClustersRequest;
use Illuminate\Support\Collection;
use Saloon\Enums\Method;
use Saloon\Laravel\Facades\Saloon;
use Redberry\LaravelCloudSdk\Tests\Fixtures\LaravelCloudFixture;

it('resolves the endpoint correctly', function () {
    $request = new ListDatabaseClustersRequest;

    expect($request->resolveEndpoint())->toBe('/databases/clusters');
});

it('has the correct HTTP method', function () {
    $request = new ListDatabaseClustersRequest;

    expect($request->getMethod())->toBe(Method::GET);
});

it('lists database clusters and returns DatabaseClusterData collection', function () {
    Saloon::fake([
        ListDatabaseClustersRequest::class => new LaravelCloudFixture('database-clusters/list'),
    ]);

    $connector = new LaravelCloudConnector(config('laravel-cloud-sdk.token'));
    $response = $connector->send(new ListDatabaseClustersRequest);

    Saloon::assertSent(ListDatabaseClustersRequest::class);
    expect($response->getPsrRequest()->getMethod())->toBe('GET');
    expect($response->getPsrRequest()->getUri()->getPath())->toBe('/api/databases/clusters');

    $dto = $response->dtoOrFail();
    expect($dto)->toBeInstanceOf(Collection::class);
    expect($dto->first())->toBeInstanceOf(DatabaseClusterData::class);
});
