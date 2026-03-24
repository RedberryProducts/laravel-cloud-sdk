<?php

use Redberry\LaravelCloudSdk\Data\Databases\DatabaseData;
use Redberry\LaravelCloudSdk\Connectors\LaravelCloudConnector;
use Redberry\LaravelCloudSdk\Requests\DatabaseClusters\ListDatabaseClustersRequest;
use Redberry\LaravelCloudSdk\Requests\Databases\ListDatabasesRequest;
use Illuminate\Support\Collection;
use Saloon\Enums\Method;
use Saloon\Laravel\Facades\Saloon;
use Redberry\LaravelCloudSdk\Tests\Fixtures\LaravelCloudFixture;

it('resolves the endpoint correctly', function () {
    $request = new ListDatabasesRequest('cluster-123');

    expect($request->resolveEndpoint())->toBe('/databases/clusters/cluster-123/databases');
});

it('has the correct HTTP method', function () {
    $request = new ListDatabasesRequest('cluster-123');

    expect($request->getMethod())->toBe(Method::GET);
});

it('lists databases and returns DatabaseData collection', function () {
    Saloon::fake([
        ListDatabaseClustersRequest::class => new LaravelCloudFixture('database-clusters/list'),
    ]);

    $connector = new LaravelCloudConnector(config('laravel-cloud-sdk.token'));
    $firstCluster = $connector->send(new ListDatabaseClustersRequest)->dtoOrFail()->first();

    Saloon::fake([
        ListDatabasesRequest::class => new LaravelCloudFixture('databases/list'),
    ]);

    $response = $connector->send(new ListDatabasesRequest($firstCluster->id));

    Saloon::assertSent(ListDatabasesRequest::class);

    $dto = $response->dtoOrFail();
    expect($dto)->toBeInstanceOf(Collection::class);
    expect($dto->first())->toBeInstanceOf(DatabaseData::class);
});
