<?php

use Redberry\LaravelCloudSdk\Connectors\LaravelCloudConnector;
use Redberry\LaravelCloudSdk\Data\DatabaseClusters\DatabaseClusterData;
use Redberry\LaravelCloudSdk\Requests\DatabaseClusters\GetDatabaseClusterRequest;
use Redberry\LaravelCloudSdk\Requests\DatabaseClusters\ListDatabaseClustersRequest;
use Redberry\LaravelCloudSdk\Tests\Fixtures\LaravelCloudFixture;
use Saloon\Enums\Method;
use Saloon\Laravel\Facades\Saloon;

it('resolves the endpoint correctly', function () {
    $request = new GetDatabaseClusterRequest('cluster-123');

    expect($request->resolveEndpoint())->toBe('/databases/clusters/cluster-123');
});

it('has the correct HTTP method', function () {
    $request = new GetDatabaseClusterRequest('cluster-123');

    expect($request->getMethod())->toBe(Method::GET);
});

it('gets a database cluster and returns DatabaseClusterData', function () {
    Saloon::fake([
        ListDatabaseClustersRequest::class => new LaravelCloudFixture('database-clusters/list'),
    ]);

    $connector = new LaravelCloudConnector(config('laravel-cloud-sdk.token'));
    $listResponse = $connector->send(new ListDatabaseClustersRequest);
    $firstCluster = $listResponse->dtoOrFail()[0];

    Saloon::fake([
        GetDatabaseClusterRequest::class => new LaravelCloudFixture('database-clusters/get'),
    ]);

    $response = $connector->send(new GetDatabaseClusterRequest($firstCluster->id));

    Saloon::assertSent(GetDatabaseClusterRequest::class);

    $dto = $response->dtoOrFail();
    expect($dto)->toBeInstanceOf(DatabaseClusterData::class);
    expect($dto->id)->toBeString();
    expect($dto->name)->toBeString();
});
