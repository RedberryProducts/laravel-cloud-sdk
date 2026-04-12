<?php

use Redberry\LaravelCloudSdk\Connectors\LaravelCloudConnector;
use Redberry\LaravelCloudSdk\Data\Databases\DatabaseData;
use Redberry\LaravelCloudSdk\Requests\DatabaseClusters\ListDatabaseClustersRequest;
use Redberry\LaravelCloudSdk\Requests\Databases\ListDatabasesRequest;
use Redberry\LaravelCloudSdk\Tests\Fixtures\LaravelCloudFixture;
use Saloon\Enums\Method;
use Saloon\Laravel\Facades\Saloon;
use Saloon\PaginationPlugin\Contracts\Paginatable;

it('resolves the endpoint correctly', function () {
    $request = new ListDatabasesRequest('cluster-123');

    expect($request->resolveEndpoint())->toBe('/databases/clusters/cluster-123/databases');
});

it('has the correct HTTP method', function () {
    $request = new ListDatabasesRequest('cluster-123');

    expect($request->getMethod())->toBe(Method::GET);
});

it('implements Paginatable', function () {
    $request = new ListDatabasesRequest('cluster-123');

    expect($request)->toBeInstanceOf(Paginatable::class);
});

it('includes relationships in default query', function () {
    $request = new ListDatabasesRequest('cluster-123');

    expect($request->query()->all())->toBe([
        'include' => 'database,environments',
    ]);
});

it('lists databases and returns DatabaseData collection', function () {
    Saloon::fake([
        ListDatabaseClustersRequest::class => new LaravelCloudFixture('database-clusters/list'),
    ]);

    $connector = new LaravelCloudConnector(config('laravel-cloud-sdk.token'));
    $firstCluster = $connector->send(new ListDatabaseClustersRequest)->dtoOrFail()[0];

    Saloon::fake([
        ListDatabasesRequest::class => new LaravelCloudFixture('databases/list'),
    ]);

    $response = $connector->send(new ListDatabasesRequest($firstCluster->id));

    Saloon::assertSent(ListDatabasesRequest::class);

    $dto = $response->dtoOrFail();
    expect($dto)->toBeArray();
    expect($dto[0])->toBeInstanceOf(DatabaseData::class);
});
