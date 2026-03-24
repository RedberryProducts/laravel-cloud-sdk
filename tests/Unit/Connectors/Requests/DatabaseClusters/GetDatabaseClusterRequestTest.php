<?php

use App\Data\LaravelCloud\DatabaseClusters\DatabaseClusterData;
use App\Http\Integrations\LaravelCloud\LaravelCloudConnector;
use App\Http\Integrations\LaravelCloud\Requests\DatabaseClusters\GetDatabaseClusterRequest;
use App\Http\Integrations\LaravelCloud\Requests\DatabaseClusters\ListDatabaseClustersRequest;
use Saloon\Enums\Method;
use Saloon\Laravel\Facades\Saloon;
use Tests\Fixtures\LaravelCloud\LaravelCloudFixture;

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

    $connector = new LaravelCloudConnector(config('laravel-cloud.token'));
    $listResponse = $connector->send(new ListDatabaseClustersRequest);
    $firstCluster = $listResponse->dtoOrFail()->first();

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
