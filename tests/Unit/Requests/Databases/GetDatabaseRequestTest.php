<?php

use Redberry\LaravelCloudSdk\Data\Databases\DatabaseData;
use Redberry\LaravelCloudSdk\Connectors\LaravelCloudConnector;
use Redberry\LaravelCloudSdk\Requests\DatabaseClusters\ListDatabaseClustersRequest;
use Redberry\LaravelCloudSdk\Requests\Databases\GetDatabaseRequest;
use Redberry\LaravelCloudSdk\Requests\Databases\ListDatabasesRequest;
use Saloon\Enums\Method;
use Saloon\Laravel\Facades\Saloon;
use Redberry\LaravelCloudSdk\Tests\Fixtures\LaravelCloudFixture;

it('resolves the endpoint correctly', function () {
    $request = new GetDatabaseRequest('cluster-123', 'db-456');

    expect($request->resolveEndpoint())->toBe('/databases/clusters/cluster-123/databases/db-456');
});

it('has the correct HTTP method', function () {
    $request = new GetDatabaseRequest('cluster-123', 'db-456');

    expect($request->getMethod())->toBe(Method::GET);
});

it('gets a database and returns DatabaseData', function () {
    Saloon::fake([
        ListDatabaseClustersRequest::class => new LaravelCloudFixture('database-clusters/list'),
    ]);

    $connector = new LaravelCloudConnector(config('laravel-cloud-sdk.token'));
    $firstCluster = $connector->send(new ListDatabaseClustersRequest)->dtoOrFail()->first();

    Saloon::fake([
        ListDatabasesRequest::class => new LaravelCloudFixture('databases/list'),
    ]);

    $firstDatabase = $connector->send(new ListDatabasesRequest($firstCluster->id))->dtoOrFail()->first();

    Saloon::fake([
        GetDatabaseRequest::class => new LaravelCloudFixture('databases/get'),
    ]);

    $response = $connector->send(new GetDatabaseRequest($firstCluster->id, $firstDatabase->id));

    Saloon::assertSent(GetDatabaseRequest::class);

    $dto = $response->dtoOrFail();
    expect($dto)->toBeInstanceOf(DatabaseData::class);
    expect($dto->id)->toBeString();
    expect($dto->name)->toBeString();
});
